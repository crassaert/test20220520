<?php

/**
 * Check if bearer in header is present and if this one matches defined BEARER_API_TOKEN
 */

namespace App\EventListener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Request;

class BearerListener
{
    private ?Request $request;
    private string $bearer;

    public function __construct(RequestStack $requestStack, ParameterBagInterface $parameterBag)
    {
        $this->request = $requestStack->getMainRequest();
        $this->bearer = $parameterBag->get('bearer_token');
    }

    /**
     * @throws \Exception
     */
    public function onKernelRequest(RequestEvent $event)
    {
        // Check if bearer is present
        $authString = trim($this->request->headers->get('Authorization'));
        $start = strpos($authString, ' ') + 1;
        $bearer = substr($authString, $start);

        if ($bearer !== $this->bearer) {
            throw new \Exception('Incorrect auth');
        }

        return;
    }
}
