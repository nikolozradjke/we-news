<?php

namespace App\Controller\Public;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Enum\UserRole;
use App\Service\CommentService;
use Symfony\Component\HttpFoundation\JsonResponse;

#[IsGranted(UserRole::ADMIN->value)]
final class CommentController extends AbstractController
{
    #[Route('/comment/delete/{id}', name: 'comment_delete')]
    public function index(Comment $comment, CommentService $service): JsonResponse
    {
        $service->remove($comment);

        return new JsonResponse(['status' => 'success']);
    }
}
