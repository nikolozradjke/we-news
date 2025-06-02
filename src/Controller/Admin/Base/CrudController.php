<?php

namespace App\Controller\Admin\Base;

use App\Traits\CrudHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class CrudController extends AdminBaseController
{
    use CrudHelper;

    protected function renderIndex(Request $request, callable $fetchPaginated, string $template): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 10;
        $items = $fetchPaginated($page, $limit);
        $totalItems = count($items);
        $totalPages = ceil($totalItems / $limit);

        return $this->render($template, [
            'items' => $items,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}