<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $nickname = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class, orphanRemoval: false)]
    private Collection $creatPost;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Topic::class, orphanRemoval: false)]
    private Collection $creatTopic;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Quote::class, orphanRemoval: false)]
    private Collection $userQuote;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Article::class)]
    private Collection $creatArticle;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commande::class)]
    private Collection $commander;

    public function __construct()
    {
        $this->creatPost = new ArrayCollection();
        $this->creatTopic = new ArrayCollection();
        $this->userQuote = new ArrayCollection();
        $this->creatArticle = new ArrayCollection();
        $this->commander = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getCreatPost(): Collection
    {
        return $this->creatPost;
    }

    public function addCreatPost(Post $creatPost): static
    {
        if (!$this->creatPost->contains($creatPost)) {
            $this->creatPost->add($creatPost);
            $creatPost->setUser($this);
        }

        return $this;
    }

    public function removeCreatPost(Post $creatPost): static
    {
        if ($this->creatPost->removeElement($creatPost)) {
            // set the owning side to null (unless already changed)
            if ($creatPost->getUser() === $this) {
                $creatPost->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Topic>
     */
    public function getCreatTopic(): Collection
    {
        return $this->creatTopic;
    }

    public function addCreatTopic(Topic $creatTopic): static
    {
        if (!$this->creatTopic->contains($creatTopic)) {
            $this->creatTopic->add($creatTopic);
            $creatTopic->setUser($this);
        }

        return $this;
    }

    public function removeCreatTopic(Topic $creatTopic): static
    {
        if ($this->creatTopic->removeElement($creatTopic)) {
            // set the owning side to null (unless already changed)
            if ($creatTopic->getUser() === $this) {
                $creatTopic->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Quote>
     */
    public function getUserQuote(): Collection
    {
        return $this->userQuote;
    }

    public function addUserQuote(Quote $userQuote): static
    {
        if (!$this->userQuote->contains($userQuote)) {
            $this->userQuote->add($userQuote);
            $userQuote->setUser($this);
        }

        return $this;
    }

    public function removeUserQuote(Quote $userQuote): static
    {
        if ($this->userQuote->removeElement($userQuote)) {
            // set the owning side to null (unless already changed)
            if ($userQuote->getUser() === $this) {
                $userQuote->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getCreatArticle(): Collection
    {
        return $this->creatArticle;
    }

    public function addCreatArticle(Article $creatArticle): static
    {
        if (!$this->creatArticle->contains($creatArticle)) {
            $this->creatArticle->add($creatArticle);
            $creatArticle->setUser($this);
        }

        return $this;
    }

    public function removeCreatArticle(Article $creatArticle): static
    {
        if ($this->creatArticle->removeElement($creatArticle)) {
            // set the owning side to null (unless already changed)
            if ($creatArticle->getUser() === $this) {
                $creatArticle->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommander(): Collection
    {
        return $this->commander;
    }

    public function addCommander(Commande $commander): static
    {
        if (!$this->commander->contains($commander)) {
            $this->commander->add($commander);
            $commander->setUser($this);
        }

        return $this;
    }

    public function removeCommander(Commande $commander): static
    {
        if ($this->commander->removeElement($commander)) {
            // set the owning side to null (unless already changed)
            if ($commander->getUser() === $this) {
                $commander->setUser(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nickname;
    }
}
