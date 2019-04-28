<?php

namespace App\Service\EntityManager\Client;

use App\Entity\IssuePicture;
use App\Form\Client\IssuePictureType;
use App\Service\EntityManager\CommonEntityManager;
use App\Service\EntityManager\Exception\ManageEntityException;
use App\Service\UserAwareServiceTrait;
use Symfony\Component\Form\FormInterface;

/**
 * Class IssuePictureManager
 * @package App\Service\EntityManager\Client
 */
class IssuePictureManager extends CommonEntityManager
{
    use UserAwareServiceTrait;

    protected function getCreationForm(): FormInterface
    {
        $picture = new IssuePicture();
        $picture->setOwner($this->getUser());

        return $this->formFactory->create(IssuePictureType::class, $picture);
    }

    protected function getUpdatingForm(): FormInterface
    {
        throw new ManageEntityException(['You can not edit issue picture!'],ManageEntityException::UPDATE_ENTITY_ERROR_TYPE);
    }
}
