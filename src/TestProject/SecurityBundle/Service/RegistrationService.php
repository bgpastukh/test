<?php declare(strict_types=1);

namespace TestProject\SecurityBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use TestProject\SecurityBundle\Entity\ReferralTransition;
use TestProject\SecurityBundle\Entity\User;

class RegistrationService
{
    /** @var EntityManager */
    private $em;

    /** @var UserPasswordEncoder */
    private $passwordEncoder;

    /** @var RequestStack */
    private $requestStack;

    public function __construct(EntityManager $em, UserPasswordEncoder $passwordEncoder, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->requestStack = $requestStack;
    }

    public function registerUser(User $user): void
    {
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encodedPassword)
            ->setIsConfirmed(false)
            ->setConfirmationToken(hash('sha512', time() . random_int(0, 1000)));

        $referralCode = $this->createUniqueReferralCode();
        $user->setReferralCode($referralCode)
            ->setReferer($this->checkLedByReferrer());

        $this->em->persist($user);
        $this->em->flush();
    }

    public function activateUser(User $user): void
    {
        $user->setIsConfirmed(true);
        $this->em->flush();
    }

    private function createUniqueReferralCode(): string
    {
        // For big project we should check same code exists in DB.
        return substr(md5(microtime() . rand()), 0, 6);
    }

    private function checkLedByReferrer(): ?User
    {
        $clientIp = $this->requestStack->getMasterRequest()->getClientIp();
        $referralTransitions = $this->em->getRepository(ReferralTransition::class)->findBy(['ip' => $clientIp]);

        if (!$referralTransitions) {
            return null;
        }
        /** @var ReferralTransition $lastTransition */
        $lastTransition = end($referralTransitions);

        return $lastTransition->getReferer();
    }
}
