<?php

namespace App\Service\EntityManager\Client;

use App\Entity\ComplaintConfirmation;
use App\Service\EntityManager\CommonEntityManager;
use App\Service\EntityManager\Exception\ManageEntityException;
use Proxies\__CG__\App\Entity\ComplaintConfirmationStatus;
use Symfony\Component\Form\FormInterface;

/**
 * Class ComplaintConfirmationManager
 * @package App\Service\EntityManager\Client
 */
class ComplaintConfirmationManager extends CommonEntityManager
{
    protected function getCreationForm(): FormInterface
    {
        throw new ManageEntityException(['message' => 'You cannot create confirmation directly!']);
    }

    protected function getUpdatingForm(): FormInterface
    {
        throw new ManageEntityException(['message' => 'You cannot update confirmation directly!']);
    }

    public function changeStatus(ComplaintConfirmation $confirmation, $statusCode)
    {
        if ($statusCode === ComplaintConfirmationStatus::STATUS_PENDING)
        {
            throw new ManageEntityException(['status' => 'The status is not allowed!']);
        }

        $status = $this
            ->entityManager
            ->getRepository('App\Entity\ComplaintConfirmationStatus')
            ->findOneBy(['code' => $statusCode]);
        if (!$status || ($status->getCode() === ComplaintConfirmationStatus::STATUS_PENDING))
        {
            throw new ManageEntityException(['status' => 'The status is not allowed!']);
        }


        $confirmation->setStatus($status);

        $this->entityManager->persist($confirmation);
        $this->entityManager->flush($confirmation);

        return $confirmation;
    }
}