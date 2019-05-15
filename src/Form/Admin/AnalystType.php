<?php

namespace App\Form\Admin;

use App\Entity\AnalystUser;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnalystType extends AccountType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => AnalystUser::class
        ]);
    }
}