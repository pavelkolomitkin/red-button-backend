<?php

namespace App\Service\EntityManager;

use App\Entity\ClientUser;
use App\Entity\User;
use App\Entity\ClientConfirmationKey;
use App\Service\EntityManager\Exception\ManageEntityException;
use App\Service\Mailer;
use Doctrine\DBAL\LockMode;
use Symfony\Component\Form\FormInterface;

class UserManager extends CommonEntityManager
{
    /**
     * @var Mailer
     */
    private $mailer;

    public function setMailer(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param array $data
     * @return ClientUser
     * @throws \Exception
     */
    public function register(array $data)
    {
        $this->entityManager->beginTransaction();

        try
        {
            /** @var ClientUser $user */
            $user = parent::create($data);

            $confirmationKey = new ClientConfirmationKey();
            $confirmationKey
                ->setKey(ClientConfirmationKey::generateRandomKey())
                ->setClient($user)
                ->setIsActivated(false);

            $this->entityManager->persist($confirmationKey);
            $this->entityManager->flush();

        }
        catch (\Exception $exception)
        {
            $this->entityManager->rollback();
            throw $exception;
        }

        $this->entityManager->commit();

        $this->mailer->sendConfirmRegistrationMessage($confirmationKey);

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
                'key' => ['This key is not valid']
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

    protected function getCreationForm(): FormInterface
    {
        return $this->formFactory->create(\App\Form\Client\ClientUserRegisterType::class);
    }

    protected function getUpdatingForm(): FormInterface
    {
        throw new ManageEntityException(['You can not edit user yet!'],ManageEntityException::UPDATE_ENTITY_ERROR_TYPE);
    }
}
