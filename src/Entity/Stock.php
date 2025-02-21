<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: StockRepository::class)]
#[Broadcast]
class Stock
{
    #[ORM\OneToOne(targetEntity: Article::class, inversedBy: "stock", cascade: ["remove"])]
    #[ORM\JoinColumn(nullable: false, unique: true)]
    private ?Article $article = null;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $article_id = null;

    #[ORM\Column]
    private ?int $nbr_article = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleId(): ?int
    {
        return $this->article_id;
    }

    public function setArticleId(int $article_id): static
    {
        $this->article_id = $article_id;

        return $this;
    }

    public function getNbrArticle(): ?int
    {
        return $this->nbr_article;
    }

    public function setNbrArticle(int $nbr_article): static
    {
        $this->nbr_article = $nbr_article;

        return $this;
    }
}
