<?php declare(strict_types=1);

namespace App\DataFixtures\Test;

use App\Entity\Contest;
use App\Entity\ContestProblem;
use App\Entity\Judging;
use App\Entity\Language;
use App\Entity\Submission;
use App\Entity\Team;
use App\Utils\Utils;
use Doctrine\Persistence\ObjectManager;

class SampleSubmissionsFixture extends AbstractTestDataFixture
{
    public function load(ObjectManager $manager): void
    {
        $submissionData = [
            // team name,         problem shortname, language, submittime,            entry point, result
            ['DOMjudge',          'hello',           'cpp',    '2021-01-01 12:34:56', null,        'success'],
            ['Example teamname',  'boolfind',        'java',   '2021-03-04 12:00:00', 'Main',      'wrong-answer'],
            ['Example teamname',  'fltcmp',          'java',   '2021-03-05 11:09:45', 'Main',      'wrong-answer'],
            ['Example teamname',  'fltcmp',          'java',   '2021-03-05 11:12:05', 'Main',      'wrong-answer'],
        ];

        /** @var Contest $contest */
        $contest = $manager->getRepository(Contest::class)->findOneBy(['shortname' => 'demo']);
        foreach ($submissionData as $index => $submissionItem) {
            $problem = $contest->getProblems()->filter(function (ContestProblem $problem) use ($submissionItem) {
                return $problem->getShortname() === $submissionItem[1];
            })->first();
            $submission = (new Submission())
                ->setContest($contest)
                ->setTeam($manager->getRepository(Team::class)->findOneBy(['name' => $submissionItem[0]]))
                ->setContestProblem($problem)
                ->setLanguage($manager->getRepository(Language::class)->find($submissionItem[2]))
                ->setSubmittime(Utils::toEpochFloat($submissionItem[3]))
                ->setEntryPoint($submissionItem[4]);
            if ($submissionItem[5] !== 'wrong-answer') {
                $problem->getProblems();
            }
            $judging = (new Judging())
                ->setContest($contest)
                ->setStarttime(Utils::toEpochFloat($submissionItem[3]))
                ->setEndtime(Utils::toEpochFloat($submissionItem[3]) + 5)
                ->setResult($submissionItem[5]);
            $submission->addJudging($judging);
            $manager->persist($submission);
            $manager->persist($judging);
            $manager->flush();
            // Add a reference, since the submission ID changes during testing because of the auto increment
            $this->addReference(sprintf('%s:%d', static::class, $index), $submission);
        }
    }
}
