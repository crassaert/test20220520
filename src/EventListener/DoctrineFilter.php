<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Doctrine\DBAL\Types\Types;

class DoctrineFilter
{
    private EntityManagerInterface $em;
    private ?Request $request;

    public function __construct(EntityManagerInterface $em, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->request = $requestStack->getMainRequest();
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if ($this->request->query->has('availabilityMin') || $this->request->query->has('availabilityMax')) {
            $filter = $this->em->getFilters()->enable('availability_filter');

            $filter->setParameter('shops', $this->request->get('shops'));

            $filter->setParameter(
                'availabilityMin',
                intval($this->request->query->get('availabilityMin', 0)),
                Types::INTEGER
            );
            $filter->setParameter(
                'availabilityMax',
                intval($this->request->query->get('availabilityMax', 99999)),
                Types::INTEGER
            );
        }
    }
}
