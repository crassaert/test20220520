<?php

namespace App\Entity;

use App\Repository\ProductAvailabilityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductAvailabilityRepository::class)]
class ProductAvailability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Shop::class, inversedBy: 'productAvailabilities')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['product_list'])]
    private $shop;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productAvailabilities')]
    #[ORM\JoinColumn(nullable: false)]
    private $product;

    #[ORM\Column(type: 'integer')]
    #[Groups(['product_list'])]
    private $availability;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): self
    {
        $this->shop = $shop;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getAvailability(): ?int
    {
        return $this->availability;
    }

    public function setAvailability(int $availability): self
    {
        $this->availability = $availability;

        return $this;
    }
}
