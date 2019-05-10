<?php


namespace App\Service\EntityManager\Client;


use App\Entity\ClientUser;
use App\Entity\Complaint;
use App\Entity\ComplaintConfirmation;
use App\Entity\ComplaintConfirmationStatus;
use App\Entity\Issue;
use App\Form\Client\IssueType;
use App\Repository\ClientUserRepository;
use App\Service\EntityManager\CommonEntityManager;
use App\Service\EntityManager\Exception\ManageEntityException;
use App\Service\Geo\Exception\GeoLocationException;
use App\Service\Geo\IGeoLocationService;
use App\Service\UserAwareServiceTrait;
use Symfony\Component\Form\FormInterface;

/**
 * Class IssueManager
 * @package App\Service\EntityManager\Client
 */
class IssueManager extends CommonEntityManager
{
    use UserAwareServiceTrait;

    /**
     * @var IGeoLocationService
     */
    private $locationService;

    protected function getCreationForm(): FormInterface
    {
        $issue = new Issue();
        $issue->setClient($this->getUser());

        return $this->formFactory->create(IssueType::class, $issue);
    }

    protected function getUpdatingForm(): FormInterface
    {
        return $this->formFactory->create(IssueType::class);
    }

    /**
     * @param IGeoLocationService $locationService
     * @return IssueManager
     *
     * @required
     */
    public function setGeoLocationService(IGeoLocationService $locationService): self
    {
        $this->locationService = $locationService;
        return $this;
    }

    /**
     * @param Issue $entity
     * @param $data
     * @throws ManageEntityException
     */
    protected function preValidate($entity, $data)
    {
        try
        {
            // get address external data
            $address = $this->locationService->getOSMAddress($data['latitude'], $data['longitude']);
        }
        catch (GeoLocationException $exception)
        {
            // if an error
            // throw exception
            throw new ManageEntityException(['latitude' => $exception->getMessage(), 'longitude' => $exception->getMessage()]);
        }

        // get region by address state
        $region = $this
            ->entityManager
            ->getRepository('App\Entity\Region')
            ->findOneBy(['title' => $address->getState()]);
        // if an error
        if (!$region)
        {
            $errorMessage = 'Cannot identify the region!';
            // throw exception
            throw new ManageEntityException(['location' => $errorMessage]);
        }

        // set new address
        // set new region
        $entity
            ->setAddress($address)
            ->setRegion($region);

        if ($entity->getId())
        {
            $this->processConfirmationsForExistingIssue($entity, $data);
        }
        else
        {
            $this->processConfirmationForNewIssue($entity, $data);
        }
    }

    /**
     * @param Issue $entity
     * @param $data
     */
    private function processConfirmationForNewIssue($entity, $data)
    {
        $complaintIds = [];

        foreach ($data['complaintConfirmations'] as $item)
        {
            if (isset($item['complaint']))
            {
                $complaintIds[] = $item['complaint'];
            }
        }


        if (!empty($complaintIds))
        {
            $complaints = $this->entityManager->getRepository('App\Entity\Complaint')
                ->createQueryBuilder('complaint')
                ->where('complaint.id in (:ids)')
                ->setParameter('ids', $complaintIds)
                ->getQuery()
                ->getResult();

            foreach ($complaints as $complaint)
            {
                $newConfirmation = new ComplaintConfirmation();
                $newConfirmation
                    ->setComplaint($complaint)
                    ->setIssue($entity);

                $entity->getComplaintConfirmations()->add($newConfirmation);
            }
        }

    }


    /**
     * @param Issue $entity
     * @param array $data
     */
    private function processConfirmationsForExistingIssue($entity, $data)
    {
        $confirmationIds = [];
        $complaintIds = [];

        foreach ($data['complaintConfirmations'] as $item)
        {
            if (isset($item['id']))
            {
                $confirmationIds[] = $item['id'];
            }
            else if (isset($item['complaint']))
            {
                $complaintIds[] = $item['complaint'];
            }
        }

        $confirmationRepository = $this->entityManager->getRepository('App\Entity\ComplaintConfirmation');
        $deletingConfirmations = [];

        if (!empty($confirmationIds))
        {
            $confirmationRepository = $this->entityManager->getRepository('App\Entity\ComplaintConfirmation');

            $deletingConfirmations = $confirmationRepository->createQueryBuilder('complaint_confirmation')
                ->where('complaint_confirmation.issue = :issue')
                ->setParameter('issue', $entity)
                ->andWhere('complaint_confirmation.id not in (:ids)')
                ->setParameter('ids', $confirmationIds)
                ->getQuery()
                ->getResult();
        }
        else
        {
            $deletingConfirmations = $confirmationRepository->createQueryBuilder('complaint_confirmation')
                ->where('complaint_confirmation.issue = :issue')
                ->setParameter('issue', $entity)
                ->getQuery()
                ->getResult();
        }

        foreach ($deletingConfirmations as $deletingConfirmation)
        {
            $entity->removeComplaintConfirmation($deletingConfirmation);
            $this->entityManager->remove($deletingConfirmation);
            $this->entityManager->flush($deletingConfirmation);

            $entity->getComplaintConfirmations()->removeElement($deletingConfirmation);
        }


        if (!empty($complaintIds))
        {
            $complaints = $this->entityManager->getRepository('App\Entity\Complaint')
                ->createQueryBuilder('complaint')
                ->where('complaint.id in (:ids)')
                ->setParameter('ids', $complaintIds)
                ->getQuery()
                ->getResult();

            foreach ($complaints as $complaint)
            {
                $newConfirmation = new ComplaintConfirmation();
                $newConfirmation
                    ->setComplaint($complaint)
                    ->setIssue($entity);

                $this->entityManager->persist($newConfirmation);
                $this->entityManager->flush($newConfirmation);

                $entity->getComplaintConfirmations()->add($newConfirmation);
            }
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

    }

    /**
     * @param Issue $entity
     * @param $data
     * @throws ManageEntityException
     */
    protected function postValidate($entity, $data)
    {

    }

    /**
     * @param Issue $entity
     * @throws ManageEntityException
     */
    public function remove($entity)
    {
        $entity->getComplaintConfirmations()->clear();

        parent::remove($entity);
    }

    public function addLike(Issue $issue, ClientUser $user)
    {
        $this->entityManager->beginTransaction();

        try
        {
            $issue->addLike($user);

            $this->entityManager->persist($issue);
            $this->entityManager->flush();

            $this->entityManager->commit();
        }
        catch (\Exception $exception)
        {
            $this->entityManager->rollback();
            throw $exception;
        }
    }

    public function removeLike(Issue $issue, ClientUser $user)
    {
        $this->entityManager->beginTransaction();

        try
        {
            $issue->removeLike($user);

            $this->entityManager->persist($issue);
            $this->entityManager->flush();

            $this->entityManager->commit();
        }
        catch (\Exception $exception)
        {
            $this->entityManager->rollback();
            throw $exception;
        }
    }

    public function hasUserLike(Issue $issue, ClientUser $user)
    {
        /** @var ClientUserRepository $repository */
        $repository = $this->entityManager->getRepository('App\Entity\ClientUser');

        $liker = $repository
            ->createQueryBuilder('client_user')
            ->join('client_user.likeIssues', 'issue', 'WITH', 'issue = :currentIssue')
            ->setParameter('currentIssue', $issue)
            ->andWhere('client_user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();

        return !empty($liker);
    }
}