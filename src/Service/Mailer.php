<?php

namespace App\Service;

use App\Entity\ClientUser;
use App\Entity\ClientConfirmationKey;
use App\Entity\PasswordRecoveryKey;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    private $templating;

    private $fromMail;

    private $linkHost;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, ParameterBagInterface $parameterBag)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;

        $this->fromMail = $parameterBag->get('noreply_mail');
        $this->linkHost = $parameterBag->get('email_link_host');
    }

    public function sendConfirmRegistrationMessage(ClientConfirmationKey $confirmationKey)
    {
        $user = $confirmationKey->getClient();

        $message = (new \Swift_Message('Welcome to Red Button'))
            ->setFrom($this->fromMail)
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('Mail\register_confirmation.html.twig', [
                    'confirmationLink' => 'http://' . $this->linkHost . '/security/register-confirm/' . $confirmationKey->getKey()]
                )
            );

        $this->mailer->send($message);
    }

    public function sendRecoverPasswordLink(PasswordRecoveryKey $key)
    {
        $user = $key->getUser();

        $message = (new \Swift_Message('Password Recovering'))
            ->setFrom($this->fromMail)
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('Mail\recovery_password_request.html.twig', [
                    'recoveryLink' => 'http://' . $this->linkHost . '/security/password-recovery/' . $key->getKey()
                ])
            )
        ;

        $this->mailer->send($message);
    }
}
