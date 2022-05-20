<?php

namespace App\Repository;

use App\Api\Filter\ProductFilter;
use App\Entity\ProductAvailability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductAvailability>
 *
 * @method ProductAvailability|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductAvailability|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductAvailability[]    findAll()
 * @method ProductAvailability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductAvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductAvailability::class);
    }

    public function add(ProductAvailability $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductAvailability $entity, bool $flush = false): void
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
        $qb = $this->createQueryBuilder('pa')
            ->select('p')
            ->leftJoin('pa.product', 'p')
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
