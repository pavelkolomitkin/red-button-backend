<?php

namespace App\Event;

use App\Entity\PasswordRecoveryKey;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserPasswordRecoveryEvent
 * @package App\Event
 */
class UserPasswordRecoveryEvent extends Event
{
    public const RECOVERY_REQUEST_EVENT = 'password_recovery.request';
    public const RECOVERY_CONFIRM_EVENT = 'password_recovery.confirm';

    /**
     * @var PasswordRecoveryKey
     */
    private $key;

    public function __construct(PasswordRecoveryKey $key)
    {
        $this->key = $key;
    }

    public function getKey()
    {
        return $this->key;
    }
}