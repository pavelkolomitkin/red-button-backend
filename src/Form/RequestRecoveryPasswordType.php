<?php

namespace App\Form;

use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RequestRecoveryPasswordType extends CommonType
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $repository
     *
     * @required
     */
    public function setUserRepository(UserRepository $repository)
    {
        $this->userRepository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, [
            'constraints' => [
                new Email(),
                new Callback(array($this, 'checkExistenceEmail'))
            ]
        ]);
    }

    public function checkExistenceEmail($value, ExecutionContextInterface $context)
    {
        $user = $this->userRepository->findOneBy(['email' => $value]);
        if (!$user)
        {
            $context->addViolation('user.email.undefined');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => null,
            'allow_extra_fields' => true
        ]);
    }
}