<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use App\Traits\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $shortDescription;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'news')]
    private Collection $categories;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'news', targetEntity: Comment::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $comments;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int 
    { 
        return $this->id; 
    }

    public function getTitle(): string 
    { 
        return $this->title; 
    }

    public function setTitle(string $title): self 
    { 
        $this->title = $title; 
        return $this; 
    }

    public function getShortDescription(): string 
    { 
        return $this->shortDescription; 
    }

    public function setShortDescription(string $shortDescription): self 
    { 
        $this->shortDescription = $shortDescription; 
        return $this; 
    }

    public function getContent(): string 
    { 
        return $this->content; 
    }

    public function setContent(string $content): self 
    {
        $this->content = $content; 
        return $this; 
    }

    public function getPicture(): ?string 
    { 
        return $this->picture; 
    }

    public function setPicture(?string $picture): self 
    { 
        $this->picture = $picture;
        return $this; 
    }

    public function getCategories(): Collection 
    { 
        return $this->categories; 
    }

    public function addCategory(Category $category): self 
    { 
        if (!$this->categories->contains($category)) 
        $this->categories->add($category); 
        return $this; 
    }

    public function removeCategory(Category $category): self 
    { 
        $this->categories->removeElement($category);
        return $this; 
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }
}
