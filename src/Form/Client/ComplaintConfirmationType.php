<?php

namespace App\Form\Client;

use App\Entity\Complaint;
use App\Entity\ComplaintConfirmation;
use App\Form\CommonType;
use App\Repository\ComplaintConfirmationStatusRepository;
use App\Service\UserAwareServiceTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComplaintConfirmationType extends CommonType
{
    use UserAwareServiceTrait;

    /**
     * @var ComplaintConfirmationStatusRepository
     */
    private $statusRepository;

    /**
     * @param ComplaintConfirmationStatusRepository $statusRepository
     * @required
     */
    public function setComplaintConfirmationRepository(ComplaintConfirmationStatusRepository $statusRepository)
    {
        $this->statusRepository = $statusRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('complaint', EntityType::class, [
                'required' => true,
                'class' => Complaint::class,
                'multiple' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ComplaintConfirmation::class,
            'allow_extra_fields' => true
        ]);
    }
}