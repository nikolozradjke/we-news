<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CategoryDto
{
    #[Assert\NotBlank(message: "Title is required.")]
    #[Assert\Length(max: 255)]
    public ?string $title = null;
}