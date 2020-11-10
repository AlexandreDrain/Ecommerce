<?php

namespace App\Entity;

use App\Repository\ResponseToProductReviewRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResponseToProductReviewRepository::class)
 */
class ResponseToProductReview
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="responseToProductReviews")
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
     * @ORM\ManyToOne(targetEntity=ProductReview::class, inversedBy="responseToProductReviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $RespondTo;

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

    public function getRespondTo(): ?ProductReview
    {
        return $this->RespondTo;
    }

    public function setRespondTo(?ProductReview $RespondTo): self
    {
        $this->RespondTo = $RespondTo;

        return $this;
    }
}
