<?php declare(strict_types=1);

namespace TestProject\SecurityBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\Container;
use TestProject\MailerBundle\Service\MailerService;
use TestProject\SecurityBundle\Entity\User;

class UserListener
{
    /** @var EntityManager */
    private $em;

    /** @var MailerService */
    private $mailer;

    /** @var  User */
    private $user;

    public function __construct(MailerService $mailerService)
    {
        $this->mailer = $mailerService;
    }

    public function prePersist(User $user, LifecycleEventArgs $args): void
    {
        $this->user = $user;
        $this->em = $args->getEntityManager();

        $this->sendConfirmationMessage();

        if ($this->user->getReferer()) {
            $this->sendRegistrationByReferrerCodeMessage();
        }
    }

    private function sendConfirmationMessage(): void
    {
        $this->mailer->sendConfirmationMessage($this->user);
    }

    private function sendRegistrationByReferrerCodeMessage(): void
    {
        $this->mailer->sendRegistrationByReferrerCodeMessage($this->user->getReferer());
    }
}
