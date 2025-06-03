<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Base\AdminBaseController;
use App\Controller\Admin\Base\CrudController;
use App\Dto\CategoryDto;
use App\Entity\Category;
use App\Form\CategoryTypeForm;
use App\Service\CategoryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;

final class CategoryController extends CrudController
{
    private const ROUTE_PREFIX = 'category';
    private const TEMPLATE_DIR = 'dashboard/category';
    
    public function index(Request $request, CategoryRepository $repo, PaginatorInterface $paginator)
    {
        return $this->renderIndex(
            $request,
            fn() => $repo->listPaginated(),
            self::TEMPLATE_DIR . '/index.html.twig',
            $paginator,
            $perPage = 10
        );
    }

    public function create(Request $request, CategoryService $service)
    {
        $dto = new CategoryDto();
        $form = $this->createForm(CategoryTypeForm::class, $dto);

        return $this->handleForm(
            $request,
            $form,
            fn() => $service->create($dto),
            self::TEMPLATE_DIR . '/add.html.twig',
            ['redirectTo' => $this->generateUrl(self::ROUTE_PREFIX . '_index')]
        );
    }

    public function edit(Category $category, Request $request, CategoryService $service)
    {
        $dto = new CategoryDto();
        $dto->title = $category->getTitle();
        $form = $this->createForm(CategoryTypeForm::class, $dto);

        return $this->handleForm(
            $request,
            $form,
            fn() => $service->update($category, $dto),
            self::TEMPLATE_DIR . '/edit.html.twig',
            [
                'redirectTo' => $this->generateUrl(self::ROUTE_PREFIX . '_index'),
            ]
        );
    }

    public function delete(Category $category, Request $request, CategoryService $service)
    {
        return $this->handleDelete(
            $request,
            self::ROUTE_PREFIX . '_delete_' . $category->getId(),
            fn() => $service->remove($category),
            self::ROUTE_PREFIX . '_index'
        );
    }
}
