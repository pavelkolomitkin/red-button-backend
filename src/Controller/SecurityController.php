<?php

namespace App\Controller;

use App\Repository\PasswordRecoveryKeyRepository;
use App\Service\EntityManager\RecoveryPasswordKeyManager;
use App\Service\EntityManager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends CommonController
{
    /**
     * @param Request $request
     * @param UserManager $manager
     * @return Response
     * @throws \Exception
     * @Route(name="security_register", path="/security/register", methods={"POST"})
     */
    public function register(Request $request, UserManager $manager)
    {
        $user = $manager->register($request->request->all());

        return $this->getResponse([
            'user' => $user
        ], Response::HTTP_CREATED,
            [],
            [static::SERIALIZE_GROUP_PRIVATE]);
    }

    /**
     * @param $confirmationKey
     * @param UserManager $manager
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @Route(name="security_confirm_register", path="/security/confirm-register/{confirmationKey}", methods={"POST"})
     */
    public function confirm($confirmationKey, UserManager $manager)
    {
        $user = $manager->confirmRegister($confirmationKey);

        return $this->getResponse([
            'user' => $user
        ], Response::HTTP_OK,
            [],
            [static::SERIALIZE_GROUP_PRIVATE]
        );
    }

    /**
     * @Route(name="security_user_profile", path="/security/profile", methods={"GET"})
     */
    public function profile()
    {
        $user = $this->getUser();

        return $this->getResponse([
            'user' => $user
        ], Response::HTTP_OK,
            [],
            [static::SERIALIZE_GROUP_PRIVATE]
        );
    }

    /**
     * @param Request $request
     *
     * @param RecoveryPasswordKeyManager $manager
     * @Route(
     *     name="security_user_recovery_request",
     *     path="/security/recovery-request",
     *     methods={"POST"}
     * )
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     */
    public function requestPasswordRecovery(Request $request, RecoveryPasswordKeyManager $manager)
    {
        $manager->create($request->request->all());

        return $this->getResponse();
    }

    /**
     * @param $key
     * @param RecoveryPasswordKeyManager $manager
     *
     * @Route(
     *     name="security_user_recovery_verify_key",
     *     path="/security/verify-recovery-key/{key}",
     *     methods={"GET"}
     * )
     * @return Response
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     */
    public function verifyRecoveryKey($key, RecoveryPasswordKeyManager $manager)
    {
        $manager->verifyKey($key);

        return $this->getResponse();
    }

    /**
     * @param $key
     * @param Request $request
     * @param UserManager $manager
     * @param RecoveryPasswordKeyManager $keyManager
     * @return Response
     *
     * @Route(
     *     name="security_user_reset_password",
     *     path="/security/reset-password/{key}",
     *     methods={"PUT"}
     * )
     * @throws \App\Service\EntityManager\Exception\ManageEntityException
     */
    public function resetPassword($key, Request $request, UserManager $manager, RecoveryPasswordKeyManager $keyManager)
    {
        $keyEntity = $keyManager->verifyKey($key);
        $manager->resetPassword($keyEntity->getUser(), $request->request->all());
        $keyManager->utilizeKey($key);

        return $this->getResponse();
    }
}
