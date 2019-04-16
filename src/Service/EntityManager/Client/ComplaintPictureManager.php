<?php

namespace App\Service\EntityManager\Client;

use App\Form\Client\ComplaintPictureType;
use App\Service\EntityManager\CommonEntityManager;
use App\Service\EntityManager\Exception\ManageEntityException;
use Symfony\Component\Form\FormInterface;

/**
 * Class ComplaintPictureManager
 * @package App\Service\EntityManager\Client
 */
class ComplaintPictureManager extends CommonEntityManager
{
    protected function getCreationForm(): FormInterface
    {
        return $this->formFactory->create(ComplaintPictureType::class);
    }

    protected function getUpdatingForm(): FormInterface
    {
        throw new ManageEntityException(['You can not edit complaint picture!'],ManageEntityException::UPDATE_ENTITY_ERROR_TYPE);
    }
}
