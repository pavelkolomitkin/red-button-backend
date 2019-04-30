<?php


namespace App\Form\Client;


use App\Entity\Issue;
use App\Form\CommonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends CommonType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message')
            ->add('company')
            ->add('serviceType')
            ->add('pictures')
            ->add('videos')
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