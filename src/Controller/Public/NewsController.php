<?php

namespace App\Controller\Public;

use App\Dto\CommentDto;
use App\Entity\Category;
use App\Entity\News;
use App\Form\CommentTypeForm;
use App\Repository\NewsRepository;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Knp\Component\Pager\PaginatorInterface;

final class NewsController extends AbstractController
{
    public function inner(News $news, Request $request, CommentService $commentService): Response
    {
        if (!$news) {
            throw new NotFoundHttpException('News not found');
        }

        $dto = new CommentDto();
        $form = $this->createForm(CommentTypeForm::class, $dto);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $comment = $commentService->saveComment($news, $dto);
                return $this->json([
                    'status' => 'success',
                    'name' => $comment->getName(),
                    'email' => $comment->getEmail(),
                    'content' => $comment->getContent(),
                    'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i')
                ]);
            }

            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $field = $error->getOrigin()->getName();
                $errors[] = ucfirst($field) . ': ' . $error->getMessage();
            }

            return $this->json(['status' => 'error', 'errors' => $errors], 400);
        }

        return $this->render('public/news/inner.html.twig', [
            'news' => $news,
            'categories' => $news->getCategories(),
            'comments' => $news->getComments(),
            'commentForm' => $form->createView()
        ]);
    }

    public function show(Category $category, Request $request, NewsRepository $newsRepository, PaginatorInterface $paginator): Response 
    {
        return $this->render('public/news/index.html.twig', [
            'category' => $category,
            'items' => $paginator->paginate(
                $newsRepository->listPaginated($category),
                $request->query->getInt('page', 1),
                10
            ),
        ]);
    }
}
