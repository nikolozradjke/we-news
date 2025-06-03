<?php

namespace App\Controller\Public;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\CommentService;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CommentController extends AbstractController
{
    public function index(Comment $comment, CommentService $service): JsonResponse
    {
        $service->remove($comment);

        return new JsonResponse(['status' => 'success']);
    }
}
