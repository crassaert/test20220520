<?php

namespace App\Controller;

use App\Api\ApiEngine;
use App\Api\Filter\ShopFilter;
use App\Entity\Manager;
use App\Entity\Shop;
use App\Repository\ShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/shop', name: 'app_shop_')]
class ShopController extends AbstractController
{
    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, ApiEngine $apiEngine): Response
    {
        $body = $request->getContent();

        $data = json_decode($body);

        $manager = $em->getRepository(Manager::class)->find($data->manager);

        $shop = new Shop();

        $shop->setName($data->name);
        $shop->setLat($data->lat);
        $shop->setLng($data->lng);
        $shop->setPostalAddress($data->postalAddress);
        $shop->setManager($manager);

        $em->persist($shop);
        $em->flush();

        return $apiEngine->fetchResponse($shop, 'shop_read');
    }

    /**
     * List shops
     *
     * Available query options :
     *
     * lat      | float     | Latitude
     * lng      | float     | Longitude
     * radius   | float     | Distance from coords in meters
     * page     | integer   | Page number
     *
     * @param Request        $request
     * @param ShopRepository $shopRepository
     * @param ApiEngine      $apiEngine
     *
     * @return Response
     */
    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(Request $request, ShopRepository $shopRepository, ApiEngine $apiEngine)
    {
        $filters = ShopFilter::setFromRequest($request);

        $shopQb = $shopRepository->getShopsFromFilter($filters);

        $shops = new Pagerfanta(
            new QueryAdapter($shopQb)
        );

        $shops->setCurrentPage($request->query->get('page', 1));

        return $apiEngine->fetchResponse($shops, 'shop_read');
    }
}
