<?php

namespace App\Command;

use App\Entity\AdminUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CreateAdminUserAccountCommand
 * @package App\Command
 */
class CreateAdminUserAccountCommand extends Command
{
    const PASSWORD_MIN_LENGTH = 6;
    const PASSWORD_MAX_LENGTH = 10;

    protected static $defaultName = 'app:create-admin-user';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @required
     */
    public function setPasswordEncoder(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param EntityManagerInterface $entityManager
     *
     * @required
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param ValidatorInterface $validator
     *
     * @required
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a new admin user')
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'An Email of the user'
            )
            ->addArgument(
            'fullName',
            InputArgument::REQUIRED,
            'The full name of the user'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = new AdminUser();

        $user
            ->setEmail($input->getArgument('email'))
            ->setFullName($input->getArgument('fullName'))
            ->setIsActive(true);

        $password = $this->askPassword($input, $output);

        $passwordHash = $this->passwordEncoder->encodePassword($user, $password);

        $user->setPassword($passwordHash);

        $validation = $this->validator->validate($user);
        if ($validation->count() > 0)
        {
            $error = $validation->get(0);
            $message = $error->getPropertyPath() . ': ' . $error->getMessage();

            throw new \Exception($message);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('Admin user has been created!');
    }

    private function askPassword(InputInterface $input, OutputInterface $output)
    {
        $interactiveHelper = $this->getHelper('question');

        $passwordQuestion = new Question('Input a password:');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);

        $password = $interactiveHelper->ask($input, $output, $passwordQuestion);

        $repeatPasswordQuestion = new Question('Repeat the password:');
        $repeatPasswordQuestion->setHidden(true);
        $repeatPasswordQuestion->setHiddenFallback(false);

        $repeatedPassword = $interactiveHelper->ask($input, $output, $repeatPasswordQuestion);

        if ($password !== $repeatedPassword)
        {
            throw new \Exception('Passwords do not mach!');
        }

        $validation = $this->validator->validate($password, [
            new NotBlank(),
            new Length(['min' => self::PASSWORD_MIN_LENGTH, 'max' => self::PASSWORD_MAX_LENGTH])
        ]);

        if ($validation->count() > 0)
        {
            throw new \Exception('Password: ' . $validation->get(0)->getMessage());
        }

        return $password;
    }
}