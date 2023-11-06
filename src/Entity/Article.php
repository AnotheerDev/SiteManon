<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\ManyToOne(inversedBy: 'creatArticle')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Quote::class)]
    private Collection $quoteArticle;

    public function __construct()
    {
        $this->quoteArticle = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Quote>
     */
    public function getQuoteArticle(): Collection
    {
        return $this->quoteArticle;
    }

    public function addQuoteArticle(Quote $quoteArticle): static
    {
        if (!$this->quoteArticle->contains($quoteArticle)) {
            $this->quoteArticle->add($quoteArticle);
            $quoteArticle->setArticle($this);
        }

        return $this;
    }

    public function removeQuoteArticle(Quote $quoteArticle): static
    {
        if ($this->quoteArticle->removeElement($quoteArticle)) {
            // set the owning side to null (unless already changed)
            if ($quoteArticle->getArticle() === $this) {
                $quoteArticle->setArticle(null);
            }
        }

        return $this;
    }

    public function getCommentCount(): int
    {
        return $this->quoteArticle->count();
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
