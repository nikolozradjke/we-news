<?php

namespace App\Controller\Public;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class NewsController extends AbstractController
{
    #[Route('/news/{id}', name: 'app_public_news')]
    public function inner($id, NewsRepository $repo): Response
    {
        $news = $repo->findWithCategories($id);
        if (!$news) {
            throw new NotFoundHttpException('News not found');
        }

        return $this->render('public/news/inner.html.twig', [
            'news' => $news,
            'categories' => $news->getCategories()
        ]);
    }
}
