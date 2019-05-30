<?php


namespace App\Event\Subscriber;


use App\Event\UserPasswordRecoveryEvent;
use App\Service\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserPasswordRecoverySubscriber implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            UserPasswordRecoveryEvent::RECOVERY_REQUEST_EVENT => 'onRecoveryRequest',
            UserPasswordRecoveryEvent::RECOVERY_CONFIRM_EVENT => 'onRecoveryConfirm'
        ];
    }

    public function onRecoveryRequest(UserPasswordRecoveryEvent $event)
    {
        $this->mailer->sendRecoverPasswordLink($event->getKey());
    }

    public function onRecoveryConfirm(UserPasswordRecoveryEvent $event)
    {

    }
}