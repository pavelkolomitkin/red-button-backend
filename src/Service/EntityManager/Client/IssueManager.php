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
    protected function postValidate($entity, $data)
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

    public function addComplaintConfirmation(Issue $issue, Complaint $complaint)
    {
        $confirmationRepository = $this->entityManager->getRepository('App\Entity\ComplaintConfirmation');
        $result = $confirmationRepository->findOneBy([
            'complaint' => $complaint,
            'issue' => $issue
        ]);

        if ($result)
        {
            return $result;
        }

        $statusRepository = $this->entityManager->getRepository('App\Entity\ComplaintConfirmationStatus');
        $status = $statusRepository->findOneBy(['code' => ComplaintConfirmationStatus::STATUS_PENDING]);

        $result = new ComplaintConfirmation();
        $result
            ->setIssue($issue)
            ->setComplaint($complaint)
            ->setStatus($status);

        $this->entityManager->persist($result);
        $this->entityManager->flush($result);

        return $result;
    }

    public function addLike(Issue $issue, ClientUser $user)
    {
        $this->entityManager->beginTransaction();

        try
        {
            if ($this->hasUserLike($issue, $user))
            {
                throw new ManageEntityException(['user' => 'You have already liked this issue!']);
            }

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
            if (!$this->hasUserLike($issue, $user))
            {
                throw new ManageEntityException(['user' => 'You have not liked this issue!']);
            }

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