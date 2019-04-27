<?php

namespace App\Form\Client;

use App\Entity\Complaint;
use App\Entity\ComplaintPicture;
use App\Entity\ServiceType;
use App\Entity\VideoMaterial;
use App\Form\CommonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ComplaintType
 * @package App\Form\Client
 */
class ComplaintType extends CommonType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message')
            ->add('serviceType', EntityType::class, [
                'required' => false,
                'class' => ServiceType::class,
                'multiple' => false
            ])
            ->add('pictures', EntityType::class, [
                'class' => ComplaintPicture::class,
                'multiple' => true,
                'by_reference' => false,
                'expanded' => true
            ])
            ->add('videos', EntityType::class, [
                'class' => VideoMaterial::class,
                'multiple' => true,
                'by_reference' => false,
                'expanded' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Complaint::class,
            'allow_extra_fields' => true
        ]);
    }
}
