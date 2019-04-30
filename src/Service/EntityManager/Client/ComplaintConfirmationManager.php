<?php


namespace App\Service\EntityManager\Client;

use App\Entity\ComplaintConfirmation;
use App\Entity\ComplaintConfirmationStatus;
use App\Form\Client\ComplaintConfirmationType;
use App\Service\EntityManager\CommonEntityManager;
use Symfony\Component\Form\FormInterface;

/**
 * Class ComplaintConfirmationManager
 * @package App\Service\EntityManager\Client
 */
class ComplaintConfirmationManager extends CommonEntityManager
{
    protected function getCreationForm(): FormInterface
    {
        $status = $this->entityManager->getRepository('App\Entity\ComplaintConfirmationStatus')->findOneBy([
            'code' => ComplaintConfirmationStatus::STATUS_PENDING
        ]);

        $confirmation = new ComplaintConfirmation();
        $confirmation->setStatus($status);

        return $this->formFactory->create(ComplaintConfirmationType::class, $confirmation);
    }

    protected function getUpdatingForm(): FormInterface
    {
        return $this->formFactory->create(ComplaintConfirmationType::class);
    }
}