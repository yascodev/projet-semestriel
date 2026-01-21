<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Lib\Auth\Session;
use App\Lib\Auth\CsrfToken;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepository;

class FormCreateArticleController extends AbstractController
{
    public function process(Request $request): Response
    {
        // Vérifier l'authentification
        if (!Session::isAuthenticated()) {
            return Response::redirect('/login');
        }

        // Récupérer les catégories et tags disponibles
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->findAll();

        $tagRepository = new TagRepository();
        $tags = $tagRepository->findAll();

        $csrfToken = CsrfToken::generate();

        return $this->render('admin/create-article', [
            'categories' => $categories,
            'tags' => $tags,
            'csrf_token' => $csrfToken
        ]);
    }
}
