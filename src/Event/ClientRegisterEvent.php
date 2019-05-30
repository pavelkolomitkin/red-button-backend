<?php


namespace App\Event;

use App\Entity\ClientConfirmationKey;;
use Symfony\Component\EventDispatcher\Event;

class ClientRegisterEvent extends Event
{
    public const NAME = 'client.register';

    /**
     * @var ClientConfirmationKey
     */
    private $confirmationKey;

    public function __construct(ClientConfirmationKey $confirmationKey)
    {
        $this->confirmationKey = $confirmationKey;
    }

    public function getConfirmationKey()
    {
        return $this->confirmationKey;
    }
}