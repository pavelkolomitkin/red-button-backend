<?php

namespace App\Service\EntityManager\Admin;

use App\Entity\Issue;
use App\Service\EntityManager\CommonEntityManager;
use Symfony\Component\Form\FormInterface;

/**
 * Class IssueManager
 * @package App\Service\EntityManager\Admin
 */
class IssueManager extends CommonEntityManager
{
    protected function getCreationForm(): FormInterface
    {
        throw new \Exception('You can not to create this entity!');
    }

    protected function getUpdatingForm(): FormInterface
    {
        throw new \Exception('You can not to edit this entity!');
    }

    /**
     * @param Issue $entity
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     */
    public function remove($entity)
    {
        $entity->getComplaintConfirmations()->clear();

        parent::remove($entity);
    }
}