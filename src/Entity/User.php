<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $balance = "0.00";

    #[ORM\Column(length: 255)]
    private ?string $profil_picture = "/images/pngegg.png";

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facturation_address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facturation_city = null;

    #[ORM\Column(nullable: true)]
    private ?int $facturation_zipcode = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: Cart::class, cascade: ["remove"])]
    private Collection $carts;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: Invoice::class, cascade: ["remove"])]
    private Collection $invoices;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->carts = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getBalance(): ?string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getProfilPicture(): ?string
    {
        return $this->profil_picture;
    }

    public function setProfilPicture(string $profil_picture): static
    {
        $this->profil_picture = $profil_picture;

        return $this;
    }

    public function getFacturationAddress(): ?string
    {
        return $this->facturation_address;
    }

    public function setFacturationAddress(?string $facturation_address): self
    {
        $this->facturation_address = $facturation_address;
        return $this;
    }

    public function getFacturationCity(): ?string
    {
        return $this->facturation_city;
    }

    public function setFacturationCity(?string $facturation_city): self
    {
        $this->facturation_city = $facturation_city;
        return $this;
    }

    public function getFacturationZipcode(): ?int
    {
        return $this->facturation_zipcode;
    }

    public function setFacturationZipcode(?int $facturation_zipcode): self
    {
        $this->facturation_zipcode = $facturation_zipcode;
        return $this;
    }

    public function getArticles(): Collection
    {
        return $this->articles;
    }
}
