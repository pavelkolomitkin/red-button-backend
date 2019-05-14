<?php

namespace App\Service\EntityManager\Admin;

use App\Entity\CompanyRepresentativeUser;
use App\Form\Admin\CompanyRepresentativeType;
use Symfony\Component\Form\FormInterface;

class CompanyRepresentativeAccountManager extends CommonAccountManager
{
    protected function getCreationForm(): FormInterface
    {
        return $this->formFactory->create(
            CompanyRepresentativeType::class,
            new CompanyRepresentativeUser(),
            ['scenario' => 'create']
        );
    }

    protected function getUpdatingForm(): FormInterface
    {
        return $this->formFactory->create(
            CompanyRepresentativeType::class,
            null,
            ['scenario' => 'update']
        );
    }
}