<?php

namespace App\Controller\Admin\Base;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Enum\UserRole;

#[IsGranted(UserRole::ADMIN->value)]
abstract class AdminBaseController extends AbstractController
{
    public const PATH = '/admin';
}