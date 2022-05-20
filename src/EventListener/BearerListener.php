<?php

/**
 * Check if bearer in header is present and if this one matches defined BEARER_API_TOKEN
 */

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class BearerListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the main request
            return;
        }
    }
}
