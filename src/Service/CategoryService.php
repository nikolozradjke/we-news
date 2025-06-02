<?php

namespace App\Service;

use App\Dto\CategoryDto;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CategoryService
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoryRepository $repo,
    ) {}

    public function create(CategoryDto $dto): Category
    {
        $category = new Category();
        $category->setTitle($dto->title);
        $category->setCreatedAt(new \DateTimeImmutable());
        $category->setUpdatedAt(new \DateTimeImmutable());

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

    public function listPaginated(int $page = 1, int $limit = 10): Paginator
    {
        return $this->repo->findAllOrderedPaginated($page, $limit);
    }

    public function update(int $id, CategoryDto $dto): ?Category
    {
        $category = $this->repo->find($id);

        if (!$category) {
            return null;
        }

        $category->setTitle($dto->title);
        $category->setUpdatedAt(new \DateTimeImmutable());

        $this->em->flush();

        return $category;
    }

    public function remove(int $id): bool
    {
        $category = $this->repo->find($id);

        if (!$category) {
            return false;
        }

        $this->em->remove($category);
        $this->em->flush();

        return true;
    }

    public function getById(int $id): ?Category
    {
        return $this->repo->find($id);
    }
}