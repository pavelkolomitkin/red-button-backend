<?php

namespace App\Service\DoctrineFilter;

use App\Entity\User;
use App\Service\UserAwareServiceTrait;
use Doctrine\ORM\EntityManagerInterface;

class Configurator
{
    use UserAwareServiceTrait;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onKernelRequest()
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user)
        {
            $activeUserFilter = $this->entityManager->getFilters()->enable('active_user');
            $activeUserFilter->setUser($user);
        }
    }
}
