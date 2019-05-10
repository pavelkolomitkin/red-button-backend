<?php

namespace App\Form\Client;

use App\Entity\Company;
use App\Entity\ComplaintConfirmation;
use App\Entity\Issue;
use App\Entity\IssuePicture;
use App\Entity\ServiceType;
use App\Entity\VideoMaterial;
use App\Form\CommonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends CommonType
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     *
     * @required
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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