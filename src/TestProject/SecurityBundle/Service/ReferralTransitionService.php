<?php declare(strict_types=1);

namespace TestProject\SecurityBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use TestProject\SecurityBundle\Entity\ReferralTransition;
use TestProject\SecurityBundle\Entity\User;

class ReferralTransitionService
{
    /** @var EntityManager */
    private $em;

    /** @var RequestStack  */
    private $requestStack;

    public function __construct(EntityManager $em, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    public function catchTransition(string $referralCode): void
    {
        $referrer = $this->em->getRepository(User::class)->findOneBy(['referralCode' => $referralCode]);
        $currentDate = new \DateTime();
        $clientIp = $this->requestStack->getMasterRequest()->getClientIp();

        $referralTransition = new ReferralTransition();
        $referralTransition->setDate($currentDate)
            ->setIp($clientIp)
            ->setReferer($referrer);

        $this->em->persist($referralTransition);
        $this->em->flush();
    }
}
