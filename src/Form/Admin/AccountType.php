<?php


namespace App\Form\Admin;

use App\Entity\User;
use App\Form\CommonType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AccountType extends CommonType
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @required
     */
    public function setPasswordEncoder(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $scenario = isset($options['action']) ? $options['action'] : 'create';

        $builder
            ->add('fullName')
            ->add('email')
            ->add('isActive');

        if ($scenario === 'create')
        {
            $builder->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_name' => 'password',
                'second_name' => 'passwordRepeat',
                'mapped' => false,
                'required' => true,
                'invalid_message' => 'user.password.should_much',
                'first_options' => [
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => User::PASSWORD_MIN_LENGTH,
                            'max' => User::PASSWORD_MAX_LENGTH,
                            'maxMessage' => 'user.password.max_length',
                            'minMessage' => 'user.password.min_length'
                            ]
                        )
                    ]
                ],
                'second_options' => [
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => User::PASSWORD_MIN_LENGTH,
                            'max' => User::PASSWORD_MAX_LENGTH,
                            'maxMessage' => 'user.password.max_length',
                            'minMessage' => 'user.password.min_length'
                        ])
                    ]
                ]
            ]);

            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                /** @var User $user */
                $user = $event->getForm()->getData();

                $newPassword = $event->getForm()->get('plainPassword')->getData();

                $newPasswordHash = $this->passwordEncoder->encodePassword($user,  $newPassword);
                $user->setPassword($newPasswordHash);
            });
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}