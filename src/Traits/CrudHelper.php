<?php

namespace App\Traits;

use App\Entity\News;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

trait CrudHelper
{
    protected function handleForm(
        Request $request,
        FormInterface $form,
        callable $onValid,
        string $template,
        array $viewData = []
    ): Response {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $onValid(); // Save/create/update logic
            return new RedirectResponse($viewData['redirectTo'] ?? '/');
        }

        return $this->render($template, array_merge([
            'form' => $form->createView()
        ], $viewData));
    }

    protected function handleDelete(
        Request $request,
        string $csrfTokenId,
        callable $onDelete,
        string $redirectRoute
    ): RedirectResponse {
        if ($this->isCsrfTokenValid($csrfTokenId, $request->request->get('_token'))) {
            $onDelete();
            $this->addFlash('success', 'Item deleted.');
        }

        return $this->redirectToRoute($redirectRoute);
    }
}
