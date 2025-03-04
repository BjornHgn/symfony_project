<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "invoices")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $deal_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(length: 255)]
    private ?string $facturation_address = null;

    #[ORM\Column(length: 255)]
    private ?string $facturation_city = null;

    #[ORM\Column]
    private ?int $facturation_zipcode = null;

    #[ORM\OneToMany(mappedBy: "invoice", targetEntity: InvoiceItem::class, cascade: ["persist", "remove"])]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getDealDate(): ?\DateTimeInterface
    {
        return $this->deal_date;
    }

    public function setDealDate(\DateTimeInterface $deal_date): static
    {
        $this->deal_date = $deal_date;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getFacturationAddress(): ?string
    {
        return $this->facturation_address;
    }

    public function setFacturationAddress(string $facturation_address): static
    {
        $this->facturation_address = $facturation_address;

        return $this;
    }

    public function getFacturationCity(): ?string
    {
        return $this->facturation_city;
    }

    public function setFacturationCity(string $facturation_city): static
    {
        $this->facturation_city = $facturation_city;

        return $this;
    }

    public function getFacturationZipcode(): ?int
    {
        return $this->facturation_zipcode;
    }

    public function setFacturationZipcode(int $facturation_zipcode): static
    {
        $this->facturation_zipcode = $facturation_zipcode;

        return $this;
    }

    /**
     * @return Collection<int, InvoiceItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(InvoiceItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setInvoice($this);
        }

        return $this;
    }

    public function removeItem(InvoiceItem $item): self
    {
        if ($this->items->removeElement($item)) {
            if ($item->getInvoice() === $this) {
                $item->setInvoice(null);
            }
        }

        return $this;
    }
}
