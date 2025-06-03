<?php

namespace App\Controller\Public;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class IndexController extends AbstractController
{
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('public/index/index.html.twig', [
            'categories' => $categoryRepository->findCategoriesWithLatestNews(3)
        ]);
    }
}
