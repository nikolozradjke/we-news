<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CommentDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    public string $content;
}