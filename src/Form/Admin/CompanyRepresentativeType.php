<?php

namespace App\Form\Admin;

use App\Entity\Company;
use App\Entity\CompanyRepresentativeUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyRepresentativeType extends AccountType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('company', EntityType::class, [
            'required' => true,
            'class' => Company::class,
            'multiple' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => CompanyRepresentativeUser::class
        ]);
    }
}