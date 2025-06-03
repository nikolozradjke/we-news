<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class AdminController extends AbstractController
{
    public function dashboard(): Response
    {
        return $this->render('dashboard/index.html.twig');
    }
}
