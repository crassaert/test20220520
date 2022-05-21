<?php

namespace App\Repository;

use App\Api\Filter\ShopFilter;
use App\Entity\Shop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Shop>
 *
 * @method Shop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shop[]    findAll()
 * @method Shop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shop::class);
    }

    public function add(Shop $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Shop $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param ShopFilter $shopFilter
     *
     * @return QueryBuilder
     */
    public function getShopsFromFilter(ShopFilter $shopFilter): QueryBuilder
    {
        $distanceQuery = '( 3959 * acos(cos(radians('.$shopFilter->getLat().'))'.
            '* cos( radians( s.lat ) )'.
            '* cos( radians( s.lng )'.
            '- radians('.$shopFilter->getLng().') )'.
            '+ sin( radians('.$shopFilter->getLng().') )'.
            '* sin( radians( s.lat ) ) ) )';

        $qb = $this->createQueryBuilder('s');

        if ($shopFilter->getName()) {
            $qb->andWhere('s.name LIKE :name')
                ->setParameter('name', '%'.$shopFilter->getName().'%');
        }

        $qb->groupBy('s');

        if (null !== $shopFilter->getLng() && null !== $shopFilter->getLat() && null !== $shopFilter->getRadius()) {
            $qb->having($distanceQuery.' < :distance');
            $qb->setParameter('distance', $shopFilter->getRadius() / 1000);
        }

        return $qb;
    }
}
