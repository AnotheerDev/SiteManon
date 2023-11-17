<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?float $price = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Classification $classificationProduct = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Image::class)]
    private Collection $photoProduct;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Cart::class)]
    private Collection $carts;

    public function __construct()
    {
        $this->photoProduct = new ArrayCollection();
        $this->carts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getClassificationProduct(): ?Classification
    {
        return $this->classificationProduct;
    }

    public function setClassificationProduct(?Classification $classificationProduct): static
    {
        $this->classificationProduct = $classificationProduct;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getPhotoProduct(): Collection
    {
        return $this->photoProduct;
    }

    public function addPhotoProduct(Image $photoProduct): static
    {
        if (!$this->photoProduct->contains($photoProduct)) {
            $this->photoProduct->add($photoProduct);
            $photoProduct->setProduct($this);
        }

        return $this;
    }

    public function removePhotoProduct(Image $photoProduct): static
    {
        if ($this->photoProduct->removeElement($photoProduct)) {
            // set the owning side to null (unless already changed)
            if ($photoProduct->getProduct() === $this) {
                $photoProduct->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): static
    {
        if (!$this->carts->contains($cart)) {
            $this->carts->add($cart);
            $cart->setProduct($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): static
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getProduct() === $this) {
                $cart->setProduct(null);
            }
        }

        return $this; 
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
