<?php

namespace App\Controller\Admin\Base;

use App\Traits\CrudHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;

abstract class CrudController extends AdminBaseController
{
    use CrudHelper;

    protected function renderIndex(Request $request, callable $fetchPaginated, string $template, PaginatorInterface $paginator, $perPage = 10): Response
    {
        return $this->render($template, [
            'pagination' => $paginator->paginate(
                $fetchPaginated(),
                $request->query->getInt('page', 1),
                $perPage
            )
        ]);
    }
}