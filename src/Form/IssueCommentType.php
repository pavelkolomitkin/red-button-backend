<?php

namespace App\Form;

use App\Entity\Issue;
use App\Entity\IssueComment;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class IssueCommentType
 * @package App\Form
 */
class IssueCommentType extends CommonType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => IssueComment::class,
            'allow_extra_fields' => true
        ]);
    }
}