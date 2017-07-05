<?php declare(strict_types=1);

namespace TestProject\SecurityBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $request = $event->getRequest();
        $referralCode = $request->query->get('ref');

        if ($referralCode) {
            $this->referralTransitionService->catchTransition($referralCode);

            $uri = preg_replace('/\?ref=[^.*]{6}/', '', $request->getRequestUri());
            $response = new RedirectResponse($uri);
            $event->setResponse($response);
        }
    }
}
