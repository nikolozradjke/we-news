<?php

namespace App\Controller\Admin;

use App\Dto\NewsDto;
use App\Entity\News;
use App\Form\NewsTypeForm;
use App\Repository\NewsRepository;
use App\Service\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Enum\UserRole;

#[Route('/admin/news')]
#[IsGranted(UserRole::ADMIN->value)]
final class NewsController extends AbstractController
{
    #[Route('/', name: 'news_index')]
    public function index(NewsRepository $repo, Request $request): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 10;
        $newsList = $repo->findPaginated($page, 10);
        $totalItems = count($newsList);
        $totalPages = ceil($totalItems / $limit);

        return $this->render('dashboard/news/index.html.twig', [
            'newsList' => $newsList,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/create', name: 'news_create')]
    public function create(Request $request, NewsService $service): Response
    {
        $dto = new NewsDto();
        $form = $this->createForm(NewsTypeForm::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->create($dto);
            return $this->redirectToRoute('news_index');
        }

        return $this->render('dashboard/news/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Create News',
            'is_edit' => false,
        ]);
    }

    #[Route('/edit/{news}', name: 'news_edit')]
    public function edit(News $news, Request $request, NewsRepository $newsRepository, NewsService $service): Response
    {
        $dto = new NewsDto();
        $dto->title = $news->getTitle();
        $dto->shortDescription = $news->getShortDescription();
        $dto->content = $news->getContent();
        $dto->categories = $news->getCategories()->toArray();

        $form = $this->createForm(NewsTypeForm::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->update($news, $dto);
            $this->addFlash('success', 'News updated successfully.');
            return $this->redirectToRoute('news_index');
        }

        return $this->render('dashboard/news/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Edit News',
            'news' => $news,
            'is_edit' => true,
        ]);
    }

    #[Route('/delete/{news}', name: 'news_delete', methods: ['POST'])]
    public function delete(News $news, Request $request, NewsService $service): Response
    {
        if ($this->isCsrfTokenValid('delete_news_' . $news->getId(), $request->request->get('_token'))) {
            $service->remove($news);
            $this->addFlash('success', 'News deleted.');
        }

        return $this->redirectToRoute('news_index');
    }
}
