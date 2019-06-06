<?php

namespace App\Command;

use App\Entity\ComplaintConfirmation;
use App\Entity\Issue;
use App\Repository\IssueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetEarlyIssueTimeCommand extends Command
{
    protected static $defaultName = 'app:set-early-issue-time';

    const RESENT_ISSUES_INTERVAL = '3 days';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     *
     * @required
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Set early time of recent issues')
            ->addArgument('interval', InputArgument::REQUIRED, 'How early the current resent issue stuff should be move in time back');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime('now');

        $startDate = $now->sub(\DateInterval::createFromDateString(self::RESENT_ISSUES_INTERVAL));

        /** @var IssueRepository $issueRepository */
        $issueRepository = $this->entityManager->getRepository('App\Entity\Issue');

        $issues = $issueRepository->createQueryBuilder('issue')
            ->where('issue.createdAt >= :startDate')
            ->setParameter('startDate', $startDate)
            ->getQuery()
            ->getResult();

        $earlyInterval = $input->getArgument('interval');
        $dataInterval = \DateInterval::createFromDateString($earlyInterval);
        foreach ($issues as $issue)
        {
            $this->moveIssueInTime($issue, $dataInterval);
        }

        $this->entityManager->flush();
    }

    private function moveIssueInTime(Issue $issue, \DateInterval $interval)
    {
        $createdAt = clone $issue->getCreatedAt();
        $createdAt->sub($interval);
        $issue->setCreatedAt($createdAt);

        $updatedAt = clone $issue->getUpdatedAt();
        $updatedAt->sub($interval);
        $issue->setUpdatedAt($updatedAt);

        $this->entityManager->persist($issue);

        /** @var ComplaintConfirmation $complaintConfirmation */
        foreach ($issue->getComplaintConfirmations() as $complaintConfirmation)
        {
            $createdAt = clone $complaintConfirmation->getComplaint()->getCreatedAt();
            $createdAt->sub($interval);
            $complaintConfirmation->getComplaint()->setCreatedAt($createdAt);

            $updatedAt = clone $complaintConfirmation->getComplaint()->getUpdatedAt();
            $updatedAt->sub($interval);
            $complaintConfirmation->getComplaint()->setUpdatedAt($updatedAt);

            $this->entityManager->persist($complaintConfirmation->getComplaint());
        }
    }
}