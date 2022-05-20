<?php

namespace App\Entity;

use App\Repository\ShopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ShopRepository::class)]
class Shop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['shop_read', 'product_list'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['shop_read', 'product_list'])]
    private $name;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 16)]
    #[Groups(['shop_read'])]
    private $lat;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 16)]
    #[Groups(['shop_read'])]
    private $lng;

    #[ORM\Column(type: 'text')]
    #[Groups(['shop_read'])]
    private $postalAddress;

    #[ORM\ManyToOne(targetEntity: Manager::class, inversedBy: 'shops')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['shop_read'])]
    private $manager;

    #[ORM\OneToMany(mappedBy: 'shop', targetEntity: ProductAvailability::class, orphanRemoval: true)]
    private $productAvailabilities;

    public function __construct()
    {
        $this->productAvailabilities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(string $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getPostalAddress(): ?string
    {
        return $this->postalAddress;
    }

    public function setPostalAddress(string $postalAddress): self
    {
        $this->postalAddress = $postalAddress;

        return $this;
    }

    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    public function setManager(?Manager $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection<int, ProductAvailability>
     */
    public function getProductAvailabilities(): Collection
    {
        return $this->productAvailabilities;
    }

    public function addProductAvailability(ProductAvailability $productAvailability): self
    {
        if (!$this->productAvailabilities->contains($productAvailability)) {
            $this->productAvailabilities[] = $productAvailability;
            $productAvailability->setShop($this);
        }

        return $this;
    }

    public function removeProductAvailability(ProductAvailability $productAvailability): self
    {
        if ($this->productAvailabilities->removeElement($productAvailability)) {
            // set the owning side to null (unless already changed)
            if ($productAvailability->getShop() === $this) {
                $productAvailability->setShop(null);
            }
        }

        return $this;
    }
}
