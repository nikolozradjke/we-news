<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class NewsDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $title;

    #[Assert\NotBlank]
    public string $shortDescription;

    #[Assert\NotBlank]
    public string $content;

    #[Assert\Image(maxSize: '2M')]
    public ?\Symfony\Component\HttpFoundation\File\UploadedFile $picture = null;

    #[Assert\Count(min: 1)]
    public array $categories = [];
}