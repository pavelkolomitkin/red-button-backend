<?php

namespace App\Service\EntityManager\Admin;

use App\Entity\AnalystUser;
use App\Form\Admin\AnalystType;
use Symfony\Component\Form\FormInterface;

/**
 * Class AnalystAccountManager
 * @package App\Service\EntityManager\Admin
 */
class AnalystAccountManager extends CommonAccountManager
{
    protected function getCreationForm(): FormInterface
    {
        return $this->formFactory->create(
            AnalystType::class,
            new AnalystUser(),
            ['scenario' => 'create']
        );
    }

    protected function getUpdatingForm(): FormInterface
    {
        return $this->formFactory->create(
            AnalystType::class,
            null,
            ['scenario' => 'update']
        );
    }
}