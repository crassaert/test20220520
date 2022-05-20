<?php

namespace App\Controller;

use App\Api\ApiEngine;
use App\Api\Filter\ProductFilter;
use App\Repository\ProductRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product', name: 'app_product_')]
class ProductController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(Request $request, ProductRepository $repository, ApiEngine $apiEngine): Response
    {
        $filters = ProductFilter::setFromRequest($request);
        $productsQb = $repository->fetchProductsFromFilters($filters);

        $adapter = new QueryAdapter($productsQb);
        $products = new Pagerfanta($adapter);
        $products->setCurrentPage($request->query->get('page', 1));

        return $apiEngine->fetchResponse($products, 'product_list');
    }
}
