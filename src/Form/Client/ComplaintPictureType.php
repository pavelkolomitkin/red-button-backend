<?php

namespace App\Form\Client;

use App\Entity\ComplaintPicture;
use App\Form\CommonType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ComplaintPictureType
 * @package App\Form\Client
 */
class ComplaintPictureType extends CommonType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('imageFile', FileType::class, ['required' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ComplaintPicture::class,
            'allow_extra_fields' => true
        ]);
    }
}
