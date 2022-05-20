<?php

namespace App\Api\Filter;

use Symfony\Component\HttpFoundation\Request;

class ProductFilter
{
    /** @var int[] */
    private array $shops = [];

    /** @var int */
    private ?int $availabilityMin = null;

    /** @var int */
    private ?int $availabilityMax = null;

    private int $page = 1;

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int[]
     */
    public function getShops(): array
    {
        return $this->shops;
    }

    /**
     * @param int[] $shops
     */
    public function setShops(array $shops): void
    {
        $this->shops = $shops;
    }

    /**
     * @return int
     */
    public function getAvailabilityMin(): ?int
    {
        return $this->availabilityMin;
    }

    /**
     * @param int $availabilityMin
     */
    public function setAvailabilityMin(?int $availabilityMin): void
    {
        $this->availabilityMin = $availabilityMin;
    }

    /**
     * @return int
     */
    public function getAvailabilityMax(): ?int
    {
        return $this->availabilityMax;
    }

    /**
     * @param int $availabilityMax
     */
    public function setAvailabilityMax(?int $availabilityMax): void
    {
        $this->availabilityMax = $availabilityMax;
    }

    public static function setFromRequest(Request $request): ProductFilter
    {
        $shops = $request->get('shops', null);


        $filter = new self();

        if ($shops) {
            $filter->setShops(
                array_map(function ($item) {
                    return intval($item);
                }, explode(',', $shops))
            );
        }

        $filter->setAvailabilityMin($request->get('availabilityMin', 0));
        $filter->setAvailabilityMax($request->get('availabilityMax', 999999));

        return $filter;
    }
}
