<?php

namespace App\Form\Client;

use App\Entity\ClientUser;
use App\Entity\User;
use App\Form\CommonType;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;

class ClientUserRegisterType extends CommonType
{
    const PASSWORD_MIN_LENGTH = 6;
    const PASSWORD_MAX_LENGTH = 10;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var PhoneNumberUtil
     */
    private $phoneNumberUtil;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, PhoneNumberUtil $phoneNumberUtil)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->phoneNumberUtil = $phoneNumberUtil;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('fullName')
            ->add('phoneNumber', PhoneNumberType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_name' => 'password',
                'second_name' => 'passwordRepeat',
                'mapped' => false,
                'required' => true,
                'invalid_message' => 'The password fields must match.',
                'first_options' => [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => self::PASSWORD_MIN_LENGTH, 'max' => self::PASSWORD_MAX_LENGTH])
                    ]
                ],
                'second_options' => [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => self::PASSWORD_MIN_LENGTH, 'max' => self::PASSWORD_MAX_LENGTH])
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

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ClientUser::class,
            'allow_extra_fields' => true
        ]);
    }
}
