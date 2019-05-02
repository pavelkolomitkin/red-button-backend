<?php

namespace App\Service\EntityManager\Client;

use App\Form\Client\ComplaintConfirmationType;
use App\Service\EntityManager\CommonEntityManager;
use App\Service\EntityManager\Exception\ManageEntityException;
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
        return $this->formFactory->create(ComplaintConfirmationType::class);
    }
}