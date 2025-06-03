<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class NewsDto
{
    public string $title;
    public string $shortDescription;
    public string $content;
    public ?UploadedFile $picture = null;
    public array $categories = [];
}