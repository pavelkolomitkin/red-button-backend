<?php

namespace App\Service;

use App\Entity\ClientConfirmationKey;
use App\Entity\PasswordRecoveryKey;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    private $templating;

    private $fromMail;

    private $linkHost;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        \Swift_Mailer $mailer,
        EngineInterface $templating,
        ParameterBagInterface $parameterBag,
        TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;

        $this->fromMail = $parameterBag->get('noreply_mail');
        $this->linkHost = $parameterBag->get('email_link_host');

        $this->translator = $translator;
    }

    public function sendConfirmRegistrationMessage(ClientConfirmationKey $confirmationKey)
    {
        $user = $confirmationKey->getClient();

        $message = (new \Swift_Message($this->translator->trans('mail.welcome_to_the_red_button')))
            ->setFrom($this->fromMail)
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('Mail\register_confirmation.html.twig', [
                    'user' => $user,
                    'confirmationLink' => 'http://' . $this->linkHost . '/security/register-confirm/' . $confirmationKey->getKey()]
                )
                , 'text/html');

        $this->mailer->send($message);
    }

    public function sendRecoverPasswordLink(PasswordRecoveryKey $key)
    {
        $user = $key->getUser();

        $message = (new \Swift_Message($this->translator->trans('mail.password_recovery')))
            ->setFrom($this->fromMail)
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('Mail\recovery_password_request.html.twig', [
                    'recoveryLink' => 'http://' . $this->linkHost . '/security/password-recovery/' . $key->getKey()
                ])
            , 'text/html')
        ;

        $this->mailer->send($message);
    }

    public function sendPasswordResetNotifyMessage(User $user)
    {
        $message = (new \Swift_Message($this->translator->trans('mail.password_has_been_reset')))
            ->setFrom($this->fromMail)
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('Mail\reset_password_notify.html.twig', [
                    'user' => $user
                ])
                , 'text/html')
        ;

        $this->mailer->send($message);
    }
}
