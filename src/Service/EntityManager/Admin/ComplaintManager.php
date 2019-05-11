<?php

namespace App\Service\EntityManager\Admin;

use App\Service\EntityManager\CommonEntityManager;
use Symfony\Component\Form\FormInterface;

/**
 * Class ComplaintManager
 * @package App\Service\EntityManager\Admin
 */
class ComplaintManager extends CommonEntityManager
{
    protected function getCreationForm(): FormInterface
    {
        throw new \Exception('You can not to create this entity!');
    }

    protected function getUpdatingForm(): FormInterface
    {
        throw new \Exception('You can not to edit this entity!');
    }
}