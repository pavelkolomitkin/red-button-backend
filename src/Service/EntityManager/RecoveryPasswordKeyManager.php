<?php

namespace App\Service\EntityManager;

use App\Entity\PasswordRecoveryKey;
use App\Event\UserPasswordRecoveryEvent;
use App\Form\RequestRecoveryPasswordType;
use App\Repository\PasswordRecoveryKeyRepository;
use App\Repository\UserRepository;
use App\Service\EntityManager\Exception\ManageEntityException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;

class RecoveryPasswordKeyManager extends CommonEntityManager
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PasswordRecoveryKeyRepository
     */
    private $repository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var int
     */
    private $passwordRecoveryKeyTtlHours;

    /**
     * @param UserRepository $repository
     *
     * @required
     */
    public function setUserRepository(UserRepository $repository)
    {
        $this->userRepository = $repository;
    }

    public function setPasswordRecoveryKeyTtl($value)
    {
        $this->passwordRecoveryKeyTtlHours = $value;
    }

    /**
     * @param PasswordRecoveryKeyRepository $repository
     *
     * @required
     */
    public function setRepository(PasswordRecoveryKeyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @required
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    private function isRecoveryKeyExpired(PasswordRecoveryKey $key)
    {
        $result = false;

        $timeDiff = $key->getCreatedAt()->diff(new \DateTime());
        $hourDifference = $timeDiff->h + ($timeDiff->days * 24);

        if ($hourDifference >= $this->passwordRecoveryKeyTtlHours)
        {
            $result = true;
        }

        return $result;
    }

    protected function getCreationForm(): FormInterface
    {
        return $this->formFactory->create(RequestRecoveryPasswordType::class);
    }

    protected function getUpdatingForm(): FormInterface
    {
        // TODO: Implement getUpdatingForm() method.
        throw new \Exception('You cannot update this entity through the form!');
    }

    public function create(array $data)
    {
        $form = $this->getCreationForm();

        $form->submit($data);
        if (!$form->isValid())
        {
            throw new ManageEntityException(
                $this->errorExtractor->extract($form),
                ManageEntityException::CREATE_ENTITY_ERROR_TYPE
            );
        }

        $user = $this->userRepository->findOneBy(['email' => $data['email']]);

        $entity = $user->getPasswordRecoveryKey();
        if (!$entity || $this->isRecoveryKeyExpired($entity))
        {
            $entity = new PasswordRecoveryKey();
            $entity->setKey(PasswordRecoveryKey::generateRandomKey());

            $user->setPasswordRecoveryKey($entity);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }

        $this->eventDispatcher->dispatch(
            UserPasswordRecoveryEvent::RECOVERY_REQUEST_EVENT,
            new UserPasswordRecoveryEvent($entity)
        );

        return $entity;
    }

    public function verifyKey(string $key)
    {
        $entity = $this->repository->findOneBy(['key' => $key]);
        if (!$entity || $this->isRecoveryKeyExpired($entity))
        {
            throw new ManageEntityException(['key' => 'password_recovery_key.invalid']);
        }

        return $entity;
    }

    public function utilizeKey($key)
    {
        $entity = $this->repository->findOneBy(['key' => $key]);

        if ($entity)
        {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        }
    }
}