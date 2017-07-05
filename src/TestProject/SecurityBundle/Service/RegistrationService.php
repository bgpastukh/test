<?php declare(strict_types=1);

namespace TestProject\SecurityBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use TestProject\SecurityBundle\Entity\User;

class RegistrationService
{
    /** @var EntityManager */
    private $em;

    /** @var UserPasswordEncoder */
    private $passwordEncoder;

    public function __construct(EntityManager $em, UserPasswordEncoder $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function registerUser(User $user): void
    {
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encodedPassword)
            ->setIsConfirmed(false)
            ->setConfirmationToken(hash('sha512', time() . random_int(0, 1000)));

        $referralCode = $this->createUniqueReferralCode();
        $user->setReferralCode($referralCode);

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
}
