<?php

namespace App\Controller\Admin;

use App\Dto\CategoryDto;
use App\Form\CategoryTypeForm;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Enum\UserRole;

#[Route('/admin/categories')]
#[IsGranted(UserRole::ADMIN->value)]
final class CategoryController extends AbstractController
{
    #[Route('/', name: 'category_index')]
    public function index(Request $request, CategoryService $service)
    {
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 10; // Items per page

        $categories = $service->listPaginated($page, $limit);
        $totalItems = count($categories);
        $totalPages = ceil($totalItems / $limit);
        return $this->render('dashboard/category/index.html.twig', [
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/create', name: 'category_create')]
    public function create(Request $request, CategoryService $service)
    {
        $dto = new CategoryDto();
        $form = $this->createForm(CategoryTypeForm::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->create($dto);
            $this->addFlash('success', 'Category created successfully!');
            return $this->redirectToRoute('category_index');
        }

        return $this->render('dashboard/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'category_edit')]
    public function edit(int $id, Request $request, CategoryService $service)
    {
        $category = $service->getById($id);
        $dto = new CategoryDto();
        $dto->title = $category->getTitle();

        $form = $this->createForm(CategoryTypeForm::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->update($id, $dto);
            $this->addFlash('success', 'Category updated successfully.');
            return $this->redirectToRoute('category_index');
        }

        return $this->render('dashboard/category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'category_delete', methods: ['POST'])]
    public function delete(int $id, Request $request, CategoryService $service)
    {
        if ($this->isCsrfTokenValid('delete_category_' . $id, $request->request->get('_token'))) {
            $service->remove($id);
            $this->addFlash('success', 'Category deleted.');
        }

        return $this->redirectToRoute('category_index');
    }
}
