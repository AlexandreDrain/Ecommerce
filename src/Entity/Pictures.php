<?php

namespace App\Entity;

use App\Repository\PicturesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PicturesRepository::class)
 */
class Pictures
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $passOfPicture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameOfPicture;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="pictures")
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nameOfPicture;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassOfPicture(): ?string
    {
        return $this->passOfPicture;
    }

    public function setPassOfPicture(string $passOfPicture): self
    {
        $this->passOfPicture = $passOfPicture;

        return $this;
    }

    public function getNameOfPicture(): ?string
    {
        return $this->nameOfPicture;
    }

    public function setNameOfPicture(string $nameOfPicture): self
    {
        $this->nameOfPicture = $nameOfPicture;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addPicture($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->removePicture($this);
        }

        return $this;
    }

}