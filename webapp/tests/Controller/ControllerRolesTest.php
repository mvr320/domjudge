<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\BaseTest;

class ControllerRolesTest extends BaseTest
{
    /*
         /// We exclude some possible urls to not break the MockData
        if (strpos($url, '/delete') !== false) {
            return [];
        }
        // Documentation is not setup in the UnitTesting framework
        if (substr($url, 0, 4) == '/doc') {
            return [];
        }
        //Remove links to local page, external or application links
        if ($url[0] == '#' || strpos($url, 'http') !== false || $url == '/logout') {
            return [];
        }
        // This yields an error, disable for now
        if (strpos($url, '/jury/auditlog') !== false ||
            strpos($url, '/jury/import-export') !== false
        ) {
            return [];
        }
        // Downloading pdfs/text or binaries dirties the report
        // Nicer solution would be to know the mimetype from the headers
        if (
            strpos($url, '/text') !== false ||
            strpos($url, '/input') !== false ||
            strpos($url, '/output') !== false ||
            strpos($url, '/export') !== false ||
            strpos($url, '/download') !== false ||
            strpos($url, '.zip') !== false
        ) {
            return [];
        }
        */
                /*// Documentation is not setup in the UnitTesting framework
                if (substr($url, 0, 4) == '/doc') {
                    return true;
                }
                // API is not functional in Testing framework
                if (substr($url, 0, 4) == '/api') {
                    return true;
                }
                // The change-contest handles a different action
                if (substr($url, 0, 21) == '/jury/change-contest/') {
                    return true;
                }
                // Remove links to local page, external or application links
                if ($url[0] == '#' || strpos($url, 'http') !== false || $url == '/logout') {
                    return true;
                }*/
    //    return false;
    //}

    /*public function urlExcluded(string $url)
    {
        if ($url[0] == '#' ||                                              // Links to local page
            strpos($url, 'http') !== false ||                        // External links
            substr($url, 0, 4) == '/doc' ||                     // Documentation is not setup
            substr($url, 0, 4) == '/api' ||                     // API is not functional in framework
            $url == '/logout' ||                                           // Application links
            substr($url, 0, 21) == '/jury/change-contest/' ||
            false
        ) {
            return true;
        }
        /*
         /// We exclude some possible urls to not break the MockData
        if (strpos($url, '/delete') !== false) {
            return [];
        }
        // Documentation is not setup in the UnitTesting framework
        if (substr($url, 0, 4) == '/doc') {
            return [];
        }
        //Remove links to local page, external or application links
        if ($url[0] == '#' || strpos($url, 'http') !== false || $url == '/logout') {
            return [];
        }
        // This yields an error, disable for now
        if (strpos($url, '/jury/auditlog') !== false ||
            strpos($url, '/jury/import-export') !== false
        ) {
            return [];
        }
        // Downloading pdfs/text or binaries dirties the report
        // Nicer solution would be to know the mimetype from the headers
        if (
            strpos($url, '/text') !== false ||
            strpos($url, '/input') !== false ||
            strpos($url, '/output') !== false ||
            strpos($url, '/export') !== false ||
            strpos($url, '/download') !== false ||
            strpos($url, '.zip') !== false
        ) {
            return [];
        }
        */
        /*// Documentation is not setup in the UnitTesting framework
        if (substr($url, 0, 4) == '/doc') {
            return true;
        }
        // API is not functional in Testing framework
        if (substr($url, 0, 4) == '/api') {
            return true;
        }
        // The change-contest handles a different action
        if (substr($url, 0, 21) == '/jury/change-contest/') {
            return true;
        }
        // Remove links to local page, external or application links
        if ($url[0] == '#' || strpos($url, 'http') !== false || $url == '/logout') {
            return true;
        }*/
/*return false;
    }*/



    /**
     * //See: https://www.oreilly.com/library/view/php-cookbook/1565926811/ch04s25.html
     * Get all combinations of roles with at minimal the starting roles
     *
     * @var string[] $start_roles Base roles for each combination
     * @var string[] $possible_roles Additional roles to add to the combination
     * @return array All possible combinations with at least the starting roles
     */
    /*protected function roleCombinations(array $start_roles, array $possible_roles)
    {
        // initialize by adding the empty set
        $results = array($start_roles);

        foreach ($possible_roles as $element) {
            foreach ($results as $combination) {
                array_push($results, array_merge(array($element), $combination));
            }
        }
        return $results;
    }*/





    /**
     * Test that having the role(s) gives access to all visible pages.
     * This test should detect mistakes where a page is disabled when the user has a
     * certain role instead of allowing when the correct role is there.
     * @var string[] $combinations
     * @var string[] $roleURLs
     */
    /*private function verifyAccess(array $combinations, array $roleURLs)
    {
        foreach ($combinations as static::$roles) {
            foreach ($roleURLs as $url) {
                if (
                    strpos($url, '/jury/change-contest/') !== false ||
                    strpos($url, 'activate') !== false ||
                    strpos($url, 'deactivate') !== false
                ) {
                    $this->crawlPage($url, 302);
                } else {
                    $this->crawlPage($url, 200);
                }
            }
        }
    }


    /**
     * Test that having the team role for example is enough to view pages of that role.
     * This test should detect mistakes where a page is disabled when the user has a
     * certain role instead of allowing when the correct role is there.
     * @var string $roleBaseURL The standard endpoint from where the user traverses the website
     * @var string[] $baseRoles The standard role of the user
     * @var string[] $optionalRoles The roles which should not restrict the viewable pages
     * @var boolean $allPages Should all possible pages be visited
     * @dataProvider provideRoleAccessData
     */
    public function  RoleAccess(string $roleBaseURL, array $baseRoles, array $optionalRoles, bool $allPages)
    {
        static::$roles = $baseRoles;
        $this->logIn();
        $urlsToCheck = $this->crawlPage($roleBaseURL, 200);
        if ($allPages) {
            $urlsToCheck = $this->getAllPages($urlsToCheck);
        }
        $combinations = $this->roleCombinations($baseRoles, $optionalRoles);
        $this->verifyAccess($combinations, $urlsToCheck);
    }














    protected static $roles = [];
    protected static $loginURL = "http://localhost/login";

    /**
     * Some URLs are not setup in the testing framework or have a function for the
     * user UX/login process, those are skipped.
     * @var string $url
     * @return boolean
     **/
    public function urlExcluded(string $url)
    {
        return ($url[0] == '#' ||                                          // Links to local page
            strpos($url, 'http') !== false ||                        // External links
            substr($url, 0, 4) == '/doc' ||                     // Documentation is not setup
            substr($url, 0, 4) == '/api' ||                     // API is not functional in framework
            strpos($url, '/delete') !== false ||                     // Breaks MockData
            $url == '/logout' ||                                           // Application links
            substr($url, 0, 21) == '/jury/change-contest/' ||
            strpos($url, '/jury') !== false ||
            false
        );
    }

    /**
     * Crawl the webpage assume this is allowed and return all other links on the page
     * @var string $url URL to crawl
     * @var int $statusCode Expected HTTP status code
     * @return string[] Found links on crawled URL
     */
    public function crawlPage(string $url, int $statusCode)
    {
        if($this->urlExcluded($url)) {
            return [];
        }
        $crawler = $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $message = var_export($response, true);
        if($response->isRedirection() && $statusCode=='403') {
            $this->assertEquals($response->headers->get('location'), $this::$loginURL);
        } else {
            $this->assertEquals($statusCode, $response->getStatusCode(), $message);
        }
        $temp = $crawler->filter('a')->extract(['href']);
        foreach ($temp as $secUrl) {
            if(strpos($secUrl, '/jury/contests/970') !== false) {
                print($url." + ".$secUrl."\n");
            }
        }
        foreach($crawler->filter('a')->extract(['href']) as $i) {
            if($i==''){
                var_dump(static::$roles);
                var_dump($crawler);
                print("Failing: ".$url."\n");
            }
        }
        return array_unique($crawler->filter('a')->extract(['href']));
    }

    /**
     * Follow all links on a list of pages while new pages are found
     *
     * @param array $urlsToCheck URLs to traverse
     * @return array Reachable pages from the provided URLs
     */
    public function getAllPages(array $urlsToCheck)
    {
        $done = array();
        do {
            $toCheck = array_diff($urlsToCheck,$done);
            foreach ($toCheck as $url) {
                if (strpos($url,'/jury/contests') !== false) {
                    continue;
                } else {
                    if (!$this->urlExcluded($url)) {
                        $urlsToCheck = array_unique(array_merge($urlsToCheck, $this->crawlPage($url, 200)));
                    }
                    $done[] = $url;
                }
            }
        }
        while (array_diff($done,$urlsToCheck));
        return $urlsToCheck;
    }

    /*
     * Finds all the pages reachable with $roles on URL $roleBaseURL with optionally traversing all links
     * @var string[] $roleBaseURL The URL of the current Roles
     * @var string[] $roles The tested Roles,
     * @var boolean $allPages Should all possible pages be visited
     */
    protected function getPagesRoles(array $roleBaseURL, array $roles, bool $allPages)
    {
        static::$roles = $roles;
        $this->logIn();
        $urlsToCheck = [];
        foreach ($roleBaseURL as $baseURL) {
            $urlsToCheck = array_merge($urlsToCheck, $this->crawlPage($baseURL, 200));
        }

        // Find all pages, currently this sometimes breaks as some routes have the same logic
        if ($allPages) {
            $urlsToCheck = $this->getAllPages($urlsToCheck);
        }
        foreach ($urlsToCheck as $newURL) {
            if ($newURL==''){
                var_dump($roles, $roleBaseURL);
            }
            $urlsToCheck = array_merge($urlsToCheck, $this->crawlPage($baseURL, 200));
        }
        return $urlsToCheck;
    }

    /**
     * Test that having for example the jury role does not allow access to the pages of other roles.
     * @var string $roleBaseURL The URL of the current Roles
     * @var string[] $roleOthersBaseURL The BaseURLs of the other roles
     * @var string[] $roles The tested Roles,
     * @var string[] $rolesOther The other Roles
     * @var boolean $allPages Should all possible pages be visited
     * @dataProvider provideRoleAccessOtherRoles
     */
    public function testRoleAccessOtherRoles(
        string $roleBaseURL,
        array $roleOthersBaseURL,
        array $roles,
        array $rolesOther,
        bool $allPages
    ) {
        $urlsToCheck        = $this->getPagesRoles([$roleBaseURL], $roles, $allPages);
        $urlsToCheckOther   = $this->getPagesRoles($roleOthersBaseURL, $rolesOther, $allPages);
        static::$roles = $roles;
        $this->logIn();
        foreach (array_diff($urlsToCheckOther, $urlsToCheck) as $url) {
            if (!$this->urlExcluded($url)) {
                if (strpos($url, '/jury/contests') !== false) {
                    print($url."\n");
                    $this->crawlPage($url, 403);
                    continue;
                    //print("This: ".$url."\n");
                } else {
                    //print($url."\n");
                    $this->crawlPage($url, 403);
                    //continue;
                }
            }
        }
    }

    /**
     * Data provider used to test role access. Each item contains:
     * - the page to visit
     * - the base roles the user has
     * - additional roles to add to the user
     * - Whether to also recursively visit linked pages
     */
    public function provideRoleAccessData()
    {
        /*yield ['/jury',     ['admin'],  ['jury','team'],            false];
        yield ['/jury',     ['jury'],   ['admin','team'],           false];
        yield ['/team',     ['team'],   ['admin','jury'],           true];
        yield ['/public',   [],         ['team','admin','jury'],    true];*/
    }

    /**
     * Data provider used to test if having one role does not add access of other roles
     * Each item contains:
     * - the base page of the tested role
     * - the base pages of the other roles
     * - the tested role
     * - the other excluded roles
     * - Whether to also recursively visit linked pages
     **/
    public function provideRoleAccessOtherRoles()
    {
        yield ['/jury',     ['/jury','/team'],  ['admin'],  ['jury','team'],            false];
        yield ['/jury',     ['/jury','/team'],  ['jury'],   ['admin','team'],           true];
        yield ['/team',     ['/jury'],          ['team'],   ['admin','jury'],           true];
        yield ['/public',   ['/jury','/team'],  [],         ['admin','jury','team'],    true];
    }
}
