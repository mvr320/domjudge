#!/bin/bash

shopt -s expand_aliases
alias trace_on='set -x'
alias trace_off='{ set +x; } 2>/dev/null'

function section_start_internal() {
	echo -e "section_start:`date +%s`:$1\r\e[0K$2"
	trace_on
}

function section_end_internal() {
	echo -e "section_end:`date +%s`:$1\r\e[0K"
	trace_on
}

alias section_start='trace_off ; section_start_internal '
alias section_end='trace_off ; section_end_internal '

set -euxo pipefail

section_start setup "Setup and install"

export PS4='(${BASH_SOURCE}:${LINENO}): - [$?] $ '

DIR=$(pwd)
GITSHA=$(git rev-parse HEAD || true)

# Set up
"$( dirname "${BASH_SOURCE[0]}" )"/base.sh

# We use the admin user as its already there for the tests
echo "DELETE FROM userrole WHERE userid=1;" | mysql domjudge
if [ "$1" == "team" ]; then
	# Add team to admin user
	echo "INSERT INTO userrole (userid, roleid) VALUES (1, 3);" | mysql domjudge
	echo "UPDATE user SET teamid = 1 WHERE userid = 1;" | mysql domjudge
elif [ "$1" == "balloon" ]; then
	# Add balloon to admin user
	echo "INSERT INTO userrole (userid, roleid) VALUES (1, 4);" | mysql domjudge
elif [ "$1" == "jury" ]; then
	# Add jury to admin user
	echo "INSERT INTO userrole (userid, roleid) VALUES (1, 2);" | mysql domjudge
elif [ "$1" == "admin" ]; then
	# Add jury to admin user
	echo "INSERT INTO userrole (userid, roleid) VALUES (1, 1);" | mysql domjudge
fi

# Add netrc file for dummy user login
echo "machine localhost login dummy password dummy" > ~/.netrc

LOGFILE="/opt/domjudge/domserver/webapp/var/log/prod.log"

function log_on_err() {
	echo -e "\\n\\n=======================================================\\n"
	echo "Symfony log:"
	if sudo test -f "$LOGFILE" ; then
		sudo cat "$LOGFILE"
	fi
}

trap log_on_err ERR

cd /opt/domjudge/domserver

# This needs to be done before we do any submission.
# 8 hours as a helper so we can adjust contest start/endtime
TIMEHELP=$((8*60*60))
# Database changes to make the REST API and event feed match better.
cat <<EOF | mysql domjudge
DELETE FROM clarification;
UPDATE contest SET starttime  = UNIX_TIMESTAMP()-$TIMEHELP WHERE cid = 2;
UPDATE contest SET freezetime = UNIX_TIMESTAMP()+15        WHERE cid = 2;
UPDATE contest SET endtime    = UNIX_TIMESTAMP()+$TIMEHELP WHERE cid = 2;
UPDATE team_category SET visible = 1;
EOF

ADMINPASS=$(cat etc/initial_admin_password.secret)

# configure and restart php-fpm
sudo cp /opt/domjudge/domserver/etc/domjudge-fpm.conf "/etc/php/7.2/fpm/pool.d/domjudge-fpm.conf"
sudo /usr/sbin/php-fpm7.2

section_end setup

ADMINPASS=$(cat etc/initial_admin_password.secret)
#export COOKIEJAR
#COOKIEJAR=$(mktemp --tmpdir)
#export CURLOPTS="--fail -sq -m 30 -b $COOKIEJAR"

# Make an initial request which will get us a session id, and grab the csrf token from it
#CSRFTOKEN=$(curl $CURLOPTS -c $COOKIEJAR "http://localhost/domjudge/login" 2>/dev/null | sed -n 's/.*_csrf_token.*value="\(.*\)".*/\1/p')
# Make a second request with our session + csrf token to actually log in
#curl $CURLOPTS -c $COOKIEJAR -F "_csrf_token=$CSRFTOKEN" -F "_username=admin" -F "_password=$ADMINPASS" "http://localhost/domjudge/login"

cd $DIR

if [ "$1" == "public" ]; then
	echo "Do not login"
else
	STANDARDS="WCAG2A Section508"
	export COOKIEJAR
	COOKIEJAR=$(mktemp --tmpdir)
	export CURLOPTS="--fail -sq -m 30 -b $COOKIEJAR"
	# Make an initial request which will get us a session id, and grab the csrf token from it
	CSRFTOKEN=$(curl $CURLOPTS -c $COOKIEJAR "http://localhost/domjudge/login" 2>/dev/null | sed -n 's/.*_csrf_token.*value="\(.*\)".*/\1/p')
	# Make a second request with our session + csrf token to actually log in
	curl $CURLOPTS -c $COOKIEJAR -F "_csrf_token=$CSRFTOKEN" -F "_username=admin" -F "_password=$ADMINPASS" "http://localhost/domjudge/login"
	cp $COOKIEJAR cookies.txt
	sed -i 's/#HttpOnly_//g' cookies.txt
	sed -i 's/\t0\t/\t1999999999\t/g' cookies.txt
fi 

wget https://github.com/validator/validator/releases/latest/download/vnu.linux.zip
unzip -q vnu.linux.zip
#RES=0
FOUNDERR=0
ACCEPTEDERR=1000
#if [ "$1" == "css" ]; then
#ACCEPTEDERR=0
#elif [ "$1" == "svg" ]; then
#ACCEPTEDERR=0
#fi

for url in public
do
	mkdir $url
	cd $url
	if [ "$1" == "public" ]; then
		echo "public uses no cookie"
	else
    		cp $DIR/cookies.txt ./
	fi
	httrack http://localhost/domjudge/$url --preserve -*doc* -*logout*
	rm index.html
	rm localhost/domjudge/css/bootstrap.min25fe.css
	rm localhost/domjudge/css/select2-bootstrap.min25fe.css
	rm localhost/domjudge/jury/config/check/phpinfo.htm || true
	cd $DIR
	$DIR/vnu-runtime-image/bin/vnu --errors-only --exit-zero-always --skip-non-css --format json $url 2> result.json #; RES=$((RES+$?))
    #trace_off
	python3 -m "json.tool" < result.json > w3cCSS$url.json
    #trace_on
	NEWFOUNDERRORS=`$DIR/vnu-runtime-image/bin/vnu --errors-only --exit-zero-always --skip-non-css --format gnu $url 2>&1 | wc -l`
	FOUNDERR=0 #$((NEWFOUNDERRORS+FOUNDERR))

	$DIR/vnu-runtime-image/bin/vnu --errors-only --exit-zero-always --skip-non-svg --format json $url 2> result.json #; RES=$((RES+$?))
    #trace_off
	python3 -m "json.tool" < result.json > w3cSVG$url.json
    #trace_on
	NEWFOUNDERRORS=`$DIR/vnu-runtime-image/bin/vnu --errors-only --exit-zero-always --skip-non-svg --format gnu $url 2>&1 | wc -l`
	FOUNDERR=0 #$((NEWFOUNDERRORS+FOUNDERR))

	$DIR/vnu-runtime-image/bin/vnu --errors-only --exit-zero-always --skip-non-html --format json $url 2> result.json #; RES=$((RES+$?))
    #trace_off
	python3 -m "json.tool" < result.json > w3cHTML$url.json
    #trace_on
    #trace_off
    #trace_on
	NEWFOUNDERRORS=`$DIR/vnu-runtime-image/bin/vnu --errors-only --exit-zero-always --skip-non-html --format gnu $url 2>&1 | grep -v "Attribute “loading” not allowed on element" | grep -v "Element “style” not allowed as child of element" | wc -l`
	#fi
	FOUNDERR=0 #$((NEWFOUNDERRORS+FOUNDERR))
    	python3 gitlab/jsontogitlab.py w3cCSS$url.json
    	python3 gitlab/jsontogitlab.py w3cSVG$url.json
    	python3 gitlab/jsontogitlab.py w3cHTML$url.json
done
# Do not hard error yet
# exit $RES
echo "Found: " $FOUNDERR
[ "$FOUNDERR" -le "$ACCEPTEDERR" ]
