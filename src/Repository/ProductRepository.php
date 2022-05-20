<?php

namespace App\Repository;

use App\Api\Filter\ProductFilter;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param ProductFilter $productFilter
     *
     * @return QueryBuilder
     */
    public function fetchProductsFromFilters(ProductFilter $productFilter): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin(
                'p.productAvailabilities',
                'pa'
            )
            ->leftJoin('pa.shop', 's')
            ->setParameter('availabilityMin', $productFilter->getAvailabilityMin())
            ->setParameter('availabilityMax', $productFilter->getAvailabilityMax())
            ->groupBy('p');

        if (count($productFilter->getShops()) > 0) {
            $qb->andWhere('s.id IN (:shopIds)')
                ->setParameter('shopIds', $productFilter->getShops());
        }

        if ($productFilter->getAvailabilityMin()) {
            $qb->andWhere('pa.availability >= :availabilityMin');
        }

        if ($productFilter->getAvailabilityMax()) {
            $qb->andWhere('pa.availability <= :availabilityMax');
        }

        return $qb;
    }
}
