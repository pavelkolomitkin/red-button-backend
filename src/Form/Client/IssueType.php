<?php

namespace App\Form\Client;

use App\Entity\Company;
use App\Entity\Issue;
use App\Entity\IssuePicture;
use App\Entity\ServiceType;
use App\Entity\VideoMaterial;
use App\Form\CommonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends CommonType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message')
            ->add('company', EntityType::class, [
                'required' => false,
                'class' => Company::class,
                'multiple' => false
            ])
            ->add('serviceType', EntityType::class, [
                'required' => false,
                'class' => ServiceType::class,
                'multiple' => false
            ])
            ->add('pictures', EntityType::class, [
                'class' => IssuePicture::class,
                'multiple' => true,
                'by_reference' => false,
                'expanded' => true
            ])
            ->add('videos', EntityType::class, [
                'class' => VideoMaterial::class,
                'multiple' => true,
                'by_reference' => false,
                'expanded' => true
            ])
            ->add('complaintConfirmations', CollectionType::class, [
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => ComplaintConfirmationType::class
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Issue::class,
            'allow_extra_fields' => true
        ]);
    }
}