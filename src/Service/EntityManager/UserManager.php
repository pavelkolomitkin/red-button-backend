<?php

namespace App\Service\EntityManager;

use App\Entity\ClientUser;
use App\Entity\User;
use App\Entity\ClientConfirmationKey;
use App\Event\ClientRegisterEvent;
use App\Event\UserPasswordResetNotifyEvent;
use App\Form\AccountResetPasswordType;
use App\Service\EntityManager\Exception\ManageEntityException;
use App\Service\Mailer;
use Doctrine\DBAL\LockMode;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;

class UserManager extends CommonEntityManager
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;


    public function setMailer(Mailer $mailer)
    {
        $this->mailer = $mailer;
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

    /**
     * @param array $data
     * @return ClientUser
     * @throws \Exception
     */
    public function register(array $data)
    {
        /** @var ClientUser $user */
        $user = parent::create($data);

        $confirmationKey = new ClientConfirmationKey();
        $confirmationKey
            ->setKey(ClientConfirmationKey::generateRandomKey())
            ->setClient($user)
            ->setIsActivated(false);

        $this->entityManager->persist($confirmationKey);
        $this->entityManager->flush($confirmationKey);

        $this
            ->eventDispatcher
            ->dispatch(
                ClientRegisterEvent::NAME,
                new ClientRegisterEvent($confirmationKey)
            );

        return $user;
    }

    /**
     * @param $confirmationKey
     * @return User
     * @throws ManageEntityException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function confirmRegister($confirmationKey)
    {
        $this->entityManager->beginTransaction();
        /** @var ClientConfirmationKey $key */
        $key = $this
            ->entityManager
            ->getRepository('App\Entity\ClientConfirmationKey')
            ->createQueryBuilder('key')
            ->where('key.key = :value')
            ->setParameter('value', $confirmationKey)
            ->andWhere('key.isActivated = false')
            ->getQuery()
            ->setLockMode(LockMode::PESSIMISTIC_WRITE)
            ->getOneOrNullResult();

        if (!$key)
        {
            $this->entityManager->rollback();
            throw new ManageEntityException([
                'key' => ['register_confirmation_key.invalid']
            ]);
        }

        $key->setIsActivated(true);

        $user = $key->getClient();
        $user->setIsActive(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->entityManager->commit();

        return $user;
    }

    public function resetPassword(User $user, array $data)
    {
        $form = $this->formFactory->create(AccountResetPasswordType::class, $user);

        $form->submit($data);
        if (!$form->isValid())
        {
            throw new ManageEntityException(
                $this->errorExtractor->extract($form),
                ManageEntityException::UPDATE_ENTITY_ERROR_TYPE
            );
        }

        $user = $form->getData();

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this
            ->eventDispatcher
            ->dispatch(
                UserPasswordResetNotifyEvent::NAME,
                new UserPasswordResetNotifyEvent($user)
            );

        return $user;
    }

    protected function getCreationForm(): FormInterface
    {
        return $this->formFactory->create(\App\Form\Client\ClientUserRegisterType::class);
    }

    protected function getUpdatingForm(): FormInterface
    {
        throw new ManageEntityException(['You can not edit user yet!'],ManageEntityException::UPDATE_ENTITY_ERROR_TYPE);
    }
}
