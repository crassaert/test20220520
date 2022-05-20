<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['product_list'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['product_list'])]
    private $name;

    #[ORM\Column(type: 'string', length: 2048)]
    #[Groups(['product_list'])]
    private $pictureUrl;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductAvailability::class, fetch: 'EAGER', orphanRemoval: true)]
    #[Groups(['product_list'])]
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

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(string $pictureUrl): self
    {
        $this->pictureUrl = $pictureUrl;

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
            $productAvailability->setProduct($this);
        }

        return $this;
    }

    public function removeProductAvailability(ProductAvailability $productAvailability): self
    {
        if ($this->productAvailabilities->removeElement($productAvailability)) {
            // set the owning side to null (unless already changed)
            if ($productAvailability->getProduct() === $this) {
                $productAvailability->setProduct(null);
            }
        }

        return $this;
    }
}
