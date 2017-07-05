<?php declare(strict_types=1);

namespace TestProject\MailerBundle\Service;

use TestProject\SecurityBundle\Entity\User;

class MailerService
{
    /** @var \Swift_Mailer */
    private $mailer;

    /** @var string */
    private $sender;

    /** @var \Twig_Environment */
    private $twig;

    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer, string $sender)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->sender = $sender;
    }

    public function sendConfirmationMessage(User $user): void
    {
        $context = [
            'subject' => 'Confirm registration',
            'name' => $user->getUsername(),
            'confirmationToken' => $user->getConfirmationToken()
        ];

        $this->sendMessage(
            'TestProjectMailerBundle:EmailTemplates:register_confirmation.html.twig',
            $context,
            $user->getEmail()
        );
    }

    public function sendRegistrationByReferrerCodeMessage(User $user): void
    {
        $context = [
            'subject' => 'Referral link activated',
            'name' => $user->getUsername()
        ];

        $this->sendMessage(
            'TestProjectMailerBundle:EmailTemplates:referral_link_activated.html.twig',
            $context,
            $user->getEmail()
        );
    }

    protected function sendMessage(string $template, array $context, string $recipient): void
    {
        $view = $this->twig->loadTemplate($template);
        $subject = $view->renderBlock('subject', $context);
        $text = $view->renderBlock('body_text', $context);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->sender)
            ->setTo($recipient)
            ->setBody($text)
            ->setContentType('text/html');

        $this->mailer->send($message);
    }
}
