<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CommentDto
{
    public string $name;

    public string $email;

    public string $content;
}