<?php

namespace App\Controller\Public;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_public')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('public/index/index.html.twig', [
            'categories' => $categoryRepository->findCategoriesWithLatestNews(3)
        ]);
    }
}
