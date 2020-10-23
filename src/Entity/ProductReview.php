<?php

namespace App\Entity;

use App\Repository\ProductReviewRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductReviewRepository::class)
 */
class ProductReview
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="productReviews")
     */
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $writedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productReviews")
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getWritedAt(): ?\DateTimeInterface
    {
        return $this->writedAt;
    }

    public function setWritedAt(\DateTimeInterface $writedAt): self
    {
        $this->writedAt = $writedAt;

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
}
