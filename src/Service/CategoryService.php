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
        return $this->save(new Category(), $dto);
    }

    public function update(Category $category, CategoryDto $dto): Category
    {
        return $this->save($category, $dto);
    }

    private function save(Category $category, CategoryDto $dto): Category
    {
        $isNew = $category->getId() === null;
        $category->setTitle($dto->title)
            ->setUpdatedAt(new \DateTimeImmutable());

        if ($isNew) {
            $category->setCreatedAt(new \DateTimeImmutable());
        }

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

    public function remove(Category $category): bool
    {
        $this->em->remove($category);
        $this->em->flush();

        return true;
    }
}