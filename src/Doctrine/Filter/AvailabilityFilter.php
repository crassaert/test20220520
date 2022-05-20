<?php

namespace App\Doctrine\Filter;

use App\Entity\ProductAvailability;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class AvailabilityFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if ($targetEntity->getReflectionClass()->name === ProductAvailability::class) {
            $conditions = [];
            $conditions[] = sprintf(
                '%s.availability BETWEEN %s AND %s',
                $targetTableAlias,
                $this->getParameter('availabilityMin'),
                $this->getParameter('availabilityMax')
            );

            if ($this->getParameter('shops')) {
                $unescShops = substr($this->getParameter('shops'), 1, -1);
                $shops = explode(',', $unescShops);
                $cleanShops = array_map(function ($shop) {
                    return intval($shop);
                }, $shops);
                $conditions[] = sprintf(
                    '%s.shop_id IN (%s)',
                    $targetTableAlias,
                    implode(',', $cleanShops)
                );
            }

            return implode(' AND ', $conditions);
        }

        return '';
    }

}
