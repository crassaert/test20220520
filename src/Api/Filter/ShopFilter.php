<?php

namespace App\Api\Filter;

use Symfony\Component\HttpFoundation\Request;

class ShopFilter
{
    private string $name;

    private ?float $lat;

    private ?float $lng;

    private ?int $radius;

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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * @param ?float $lat
     */
    public function setLat(?float $lat): void
    {
        $this->lat = $lat;
    }

    /**
     * @return ?float
     */
    public function getLng(): ?float
    {
        return $this->lng;
    }

    /**
     * @param ?float $lng
     */
    public function setLng(?float $lng): void
    {
        $this->lng = $lng;
    }

    /**
     * @return ?int
     */
    public function getRadius(): ?int
    {
        return $this->radius;
    }

    /**
     * @param ?int $radius
     */
    public function setRadius(?int $radius): void
    {
        $this->radius = $radius;
    }

    public static function setFromRequest(Request $request): ShopFilter
    {
        $filter = new self();

        $filter->setLng($request->get('lng'));
        $filter->setLat($request->get('lat'));
        $filter->setRadius($request->get('radius'));
        $filter->setName($request->get('name', ''));

        return $filter;
    }
}
