<?php

namespace App\Controller\Client;

use App\Controller\CommonController;
use App\Entity\ComplaintConfirmation;
use App\Entity\ComplaintConfirmationStatus;
use App\Repository\ClientUserRepository;
use App\Repository\ComplaintConfirmationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller\Client
 */
class UserController extends CommonController
{
    /**
     * @param EntityManagerInterface $entityManager
     * @return Response
     *
     * @Route(name="client_common_info", path="/common-info", methods={"GET"})
     */
    public function getCommonInfo(EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        /** @var ClientUserRepository $userRepository */
        $userRepository = $entityManager->getRepository('App\Entity\ClientUser');

        $numbers = $userRepository->createQueryBuilder('client_user')
            ->select('COUNT(complaints) as complaintNumber, COUNT(issues) as issueNumber')
            ->leftJoin('client_user.complaints', 'complaints')
            ->leftJoin('client_user.issues', 'issues')
            ->where('client_user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getArrayResult();


        /** @var ComplaintConfirmationRepository $complaintConfirmationRepository */
        $complaintConfirmationRepository = $entityManager->getRepository('App\Entity\ComplaintConfirmation');

        $confirmationInfo = $complaintConfirmationRepository
            ->createQueryBuilder('confirmation')
            ->addSelect('confirmation')
            ->addSelect('COUNT(confirmation) as newConfirmationNumber')
            ->leftJoin('confirmation.complaint', 'complaint', 'WITH', 'complaint.client = :client')
            ->join('confirmation.status', 'status', 'WITH', 'status.code = :statusCode')
            ->setParameter('client', $user)
            ->setParameter('statusCode', ComplaintConfirmationStatus::STATUS_PENDING)
            ->getQuery()
            ->getResult();


        return $this->getResponse(array_merge($numbers, $confirmationInfo));
    }
}