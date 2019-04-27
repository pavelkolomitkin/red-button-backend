<?php

namespace App\Service\EntityManager\Client;

use App\Entity\ComplaintPicture;
use App\Form\Client\ComplaintPictureType;
use App\Service\EntityManager\CommonEntityManager;
use App\Service\EntityManager\Exception\ManageEntityException;
use App\Service\UserAwareServiceTrait;
use Symfony\Component\Form\FormInterface;

/**
 * Class ComplaintPictureManager
 * @package App\Service\EntityManager\Client
 */
class ComplaintPictureManager extends CommonEntityManager
{
    use UserAwareServiceTrait;

    protected function getCreationForm(): FormInterface
    {
        $picture = new ComplaintPicture();
        $picture->setOwner($this->getUser());

        return $this->formFactory->create(ComplaintPictureType::class, $picture);
    }

    protected function getUpdatingForm(): FormInterface
    {
        throw new ManageEntityException(['You can not edit complaint picture!'],ManageEntityException::UPDATE_ENTITY_ERROR_TYPE);
    }
}
