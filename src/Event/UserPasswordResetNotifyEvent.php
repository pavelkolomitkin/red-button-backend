<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserPasswordResetNotifyEvent extends Event
{
    public const NAME = 'password_reset.notify';

    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}