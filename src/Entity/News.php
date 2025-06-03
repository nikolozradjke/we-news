<?php

namespace App\Entity;

use App\Traits\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class News
{
    use Timestamps;

    private ?int $id = null;

    private string $title;

    private string $shortDescription;

    private string $content;

    private ?string $picture = null;

    private Collection $categories;

    private ?\DateTimeImmutable $createdAt = null;

    private ?\DateTimeImmutable $updatedAt = null;

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

    public function setTimestamps(): void
    {
        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    public function updateTimestamp(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
