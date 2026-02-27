<?php

namespace App\Controllers\Errors;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class PageNotFoundController extends AbstractController
{
    public function process(Request $request): Response
    {
        $this->request = $request;
        $response = $this->render('errors/404');
        $response->setStatus(404);
        return $response;
    }
}
