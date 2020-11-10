<?php

namespace App\Entity;

use App\Repository\ProductReviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=ResponseToProductReview::class, mappedBy="RespondTo")
     */
    private $responseToProductReviews;

    public function __construct()
    {
        $this->responseToProductReviews = new ArrayCollection();
    }

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

    /**
     * @return Collection|ResponseToProductReview[]
     */
    public function getResponseToProductReviews(): Collection
    {
        return $this->responseToProductReviews;
    }

    public function addResponseToProductReview(ResponseToProductReview $responseToProductReview): self
    {
        if (!$this->responseToProductReviews->contains($responseToProductReview)) {
            $this->responseToProductReviews[] = $responseToProductReview;
            $responseToProductReview->setRespondTo($this);
        }

        return $this;
    }

    public function removeResponseToProductReview(ResponseToProductReview $responseToProductReview): self
    {
        if ($this->responseToProductReviews->contains($responseToProductReview)) {
            $this->responseToProductReviews->removeElement($responseToProductReview);
            // set the owning side to null (unless already changed)
            if ($responseToProductReview->getRespondTo() === $this) {
                $responseToProductReview->setRespondTo(null);
            }
        }

        return $this;
    }
}
