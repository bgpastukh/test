<?php declare(strict_types=1);

namespace TestProject\SecurityBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use TestProject\SecurityBundle\Service\ReferralTransitionService;

class RequestListener
{
    /** @var ReferralTransitionService */
    private $referralTransitionService;

    public function __construct(ReferralTransitionService $referralTransitionService)
    {
        $this->referralTransitionService = $referralTransitionService;
    }

    public function onKernelRequest(GetResponseEvent $event): void
    {
        $referralCode = $event->getRequest()->query->get('ref');

        if ($referralCode) {
            $this->referralTransitionService->catchTransition($referralCode);
        }
    }
}
