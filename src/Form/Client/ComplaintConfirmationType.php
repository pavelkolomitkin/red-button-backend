<?php

namespace App\Form\Client;


use App\Entity\Complaint;
use App\Entity\ComplaintConfirmation;
use App\Entity\ComplaintConfirmationStatus;
use App\Entity\Issue;
use App\Form\CommonType;
use App\Service\UserAwareServiceTrait;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\Constraint\Callback;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ComplaintConfirmationType extends CommonType
{
    use UserAwareServiceTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $groups = $options['validation_groups'];
        $user = $this->getUser();

        $builder
            ->add('issue', EntityType::class, [
                'required' => true,
                'class' => Issue::class,
                'multiple' => false,
                'constraints' => [
                    new Callback(function($value, ExecutionContextInterface $context) use ($user, $groups) {

                        if (!in_array('create', $groups))
                        {
                            return;
                        }

                        /** @var Issue $value */
                        if ($user !== $value->getClient())
                        {
                            $context
                                ->buildViolation('You can create request only from your own issue!')
                                ->addViolation();
                        }

                    })
                ]
            ])
            ->add('complaint', EntityType::class, [
                'required' => true,
                'class' => Complaint::class,
                'multiple' => false,
                'constraints' => [
                    new Callback(function($value, ExecutionContextInterface $context) use ($user, $groups) {

                        if (!in_array('update', $groups))
                        {
                            return;
                        }

                        /** @var Complaint $value */
                        if ($user !== $value->getClient())
                        {
                            $context
                                ->buildViolation('You can response only from your own complaint!')
                                ->addViolation();
                        }

                    })
                ]
            ])
            ->add('status', EntityType::class, [
                'required' => true,
                'class' => ComplaintConfirmationStatus::class,
                'multiple' => false,
                'query_builder' => function(EntityRepository $repository) use ($groups) {

                    $result = $repository->createQueryBuilder('status');

                    $result->andWhere('status.code in :availableStatuses');

                    if (in_array('create', $groups))
                    {
                        $result->setParameter('availableStatuses', [
                            ComplaintConfirmationStatus::STATUS_PENDING
                        ]);
                    }
                    else if (in_array('update', $groups))
                    {
                        $result->setParameter('availableStatuses', [
                            ComplaintConfirmationStatus::STATUS_CONFIRMED,
                            ComplaintConfirmationStatus::STATUS_REJECTED
                        ]);
                    }
                    else
                    {
                        $result->setParameter('availableStatuses', [
                            ComplaintConfirmationStatus::STATUS_PENDING,
                            ComplaintConfirmationStatus::STATUS_CONFIRMED,
                            ComplaintConfirmationStatus::STATUS_REJECTED
                        ]);
                    }

                    return $result;
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ComplaintConfirmation::class,
            'allow_extra_fields' => true,
            'validation_groups' => ['create', 'update']
        ]);
    }
}