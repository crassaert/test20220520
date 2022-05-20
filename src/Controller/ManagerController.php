<?php

namespace App\Controller;

use App\Api\ApiEngine;
use App\Repository\ManagerRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/manager', name: 'app_manager_')]
class ManagerController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(Request $request, ManagerRepository $managerRepository, ApiEngine $apiEngine): Response
    {
        $managersQb = $managerRepository->createQueryBuilder('m');

        $adapter = new QueryAdapter($managersQb);
        $managers = new Pagerfanta($adapter);
        $managers->setCurrentPage($request->query->get('page', 1));

        return $apiEngine->fetchResponse($managers, 'manager_list');
    }
}
