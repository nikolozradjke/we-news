<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Base\AdminBaseController;
use App\Controller\Admin\Base\CrudController;
use App\Dto\NewsDto;
use App\Entity\News;
use App\Form\NewsTypeForm;
use App\Repository\NewsRepository;
use App\Service\NewsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route(AdminBaseController::PATH . '/news')]
final class NewsController extends CrudController
{
    private const ROUTE_PREFIX = 'news';
    private const TEMPLATE_DIR = 'dashboard/news';

    #[Route('/', name: self::ROUTE_PREFIX . '_index')]
    public function index(NewsRepository $repo, Request $request, PaginatorInterface $paginator)
    {
        return $this->renderIndex(
            $request,
            fn() => $repo->listPaginated(),
            self::TEMPLATE_DIR . '/index.html.twig',
            $paginator,
            $perPage = 10
        );
    }

    #[Route('/create', name: self::ROUTE_PREFIX . '_create')]
    public function create(Request $request, NewsService $service): Response
    {
        $dto = new NewsDto();
        $form = $this->createForm(NewsTypeForm::class, $dto);

        return $this->handleForm(
            $request,
            $form,
            fn() => $service->create($dto),
            self::TEMPLATE_DIR . '/add.html.twig',
            ['redirectTo' => $this->generateUrl(self::ROUTE_PREFIX . '_index')]
        );
    }

    #[Route('/edit/{news}', name: self::ROUTE_PREFIX . '_edit')]
    public function edit(News $news, Request $request, NewsService $service): Response
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
            return $this->redirectToRoute(self::ROUTE_PREFIX . '_index');
        }

        return $this->render(self::TEMPLATE_DIR . '/edit.html.twig', [
            'form' => $form->createView(),
            'news' => $news
        ]);
    }

    #[Route('/delete/{news}', name: self::ROUTE_PREFIX . '_delete', methods: ['POST'])]
    public function delete(News $news, Request $request, NewsService $service): Response
    {
        return $this->handleDelete(
            $request,
            self::ROUTE_PREFIX . '_delete_' . $news->getId(),
            fn() => $service->remove($news),
            self::ROUTE_PREFIX . '_index'
        );
    }
}
