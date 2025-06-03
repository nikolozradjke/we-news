<?php

namespace App\Entity;

use App\Traits\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Category
{
    use Timestamps;

    private ?int $id = null;

    private string $title;

    private ?\DateTimeImmutable $createdAt = null;

    private ?\DateTimeImmutable $updatedAt = null;

    private Collection $news;

    public function __construct()
    {
        $this->news = new ArrayCollection();
    }

    public function getNews(): Collection
    {
        return $this->news;
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
