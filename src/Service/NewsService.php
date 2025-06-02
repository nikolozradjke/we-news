<?php

namespace App\Service;

use App\Dto\NewsDto;
use App\Entity\News;
use App\Repository\CategoryRepository;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class NewsService
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoryRepository $categoryRepo,
        private NewsRepository $repo,
    ) {}

    public function create(NewsDto $dto): News
    {
        return $this->save(new News(), $dto);
    }

    public function update(News $news, NewsDto $dto): News
    {
        return $this->save($news, $dto);
    }

    private function save(News $news, NewsDto $dto): News
    {
        $isNew = $news->getId() === null;
        $news->setTitle($dto->title)
            ->setShortDescription($dto->shortDescription)
            ->setContent($dto->content)
            ->setUpdatedAt(new \DateTimeImmutable());

        if ($isNew) {
            $news->setCreatedAt(new \DateTimeImmutable());
        }    

        if ($dto->picture instanceof UploadedFile) {
            $uploadDir = 'uploads/news';
            $filename = uniqid().'.'.$dto->picture->guessExtension();
            $dto->picture->move($uploadDir, $filename);
            $news->setPicture($uploadDir . '/' . $filename);
        }

        $news->getCategories()->clear();
        foreach ($dto->categories as $catId) {
            $category = $this->categoryRepo->find($catId);
            if ($category) {
                $news->addCategory($category);
            }
        }

        $this->em->persist($news);
        $this->em->flush();

        return $news;
    }

    public function remove(News $news)
    {
        $this->em->remove($news);
        $this->em->flush();

        return true;
    }
}