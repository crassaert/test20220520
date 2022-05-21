<?php

namespace App\Controller;

use App\Api\ApiEngine;
use App\Api\Filter\ProductFilter;
use App\Entity\Product;
use App\Entity\ProductAvailability;
use App\Entity\Shop;
use App\Repository\ProductAvailabilityRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product', name: 'app_product_')]
class ProductController extends AbstractController
{
    /**
     * List products
     *
     * Available options :
     *
     * availabilityMin  | integer   | Minimum availability
     * availabilityMax  | integer   | Maximum availability
     * shops            | string    | Shop ids separated with commas
     * page             | integer   | Page number
     *
     * @param Request $request
     * @param ProductRepository $repository
     * @param ApiEngine $apiEngine
     *
     * @return Response
     */
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

    /**
     * Set product availability
     *
     * @param Request                $request
     * @param EntityManagerInterface $em
     * @param ApiEngine              $apiEngine
     *
     * @return Response
     */
    #[Route('/availability', name: 'availability', methods: ['POST'])]
    public function setAvailability(Request $request, EntityManagerInterface $em, ApiEngine $apiEngine)
    {
        $input = json_decode($request->getContent());
        $results = [];

        if (is_array($input)) {
            foreach ($input as $productAvailability) {
                // Check if availability already exists for this shop
                $item = $em->getRepository(ProductAvailability::class)->findOneBy(
                    ['shop' => $productAvailability->shop, 'product' => $productAvailability->product]
                );

                if (!$item) {
                    $item = new ProductAvailability();
                    $item->setShop($em->getRepository(Shop::class)->find($productAvailability->shop));
                    $item->setProduct($em->getRepository(Product::class)->find($productAvailability->product));
                }

                $item->setAvailability($productAvailability->availability);

                $em->persist($item);
                $em->flush();

                $results[] = $item;
            }
        }

        return $apiEngine->fetchResponse($results, 'product_availability');
    }
}
