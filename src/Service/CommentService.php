<?php

namespace App\Service;

use App\Dto\CommentDto;
use App\Entity\Comment;
use App\Entity\News;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function saveComment(News $news, CommentDto $dto): Comment
    {
        $comment = new Comment();
        $comment->setName($dto->name);
        $comment->setEmail($dto->email);
        $comment->setContent($dto->content);
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setUpdatedAt(new \DateTimeImmutable());
        $comment->setNews($news);

        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }

    public function remove(Comment $comment)
    {
        $this->em->remove($comment);
        $this->em->flush();

        return true;
    }
}