<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Lib\Auth\Session;
use App\Lib\Auth\CsrfToken;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;

class FormEditArticleController extends AbstractController
{
    public function process(Request $request): Response
    {
        // Vérifier l'authentification
        if (!Session::isAuthenticated()) {
            return Response::redirect('/login');
        }

        $articleId = (int) $request->getSlug('id');
        $articleRepository = new ArticleRepository();
        $article = $articleRepository->find($articleId);

        // Vérifier que l'article existe
        if (!$article) {
            return Response::redirect('/admin/articles?error=Article+non+trouvé');
        }

        // Vérifier les permissions
        $currentUserId = Session::get('user_id');
        $userRepository = new UserRepository();
        $currentUser = $userRepository->find($currentUserId);

        if (!$currentUser) {
            return Response::redirect('/login');
        }

        // Les auteurs ne peuvent éditer que leurs articles
        if ($currentUser->role === 'author' && $article->author_id !== $currentUserId) {
            return Response::redirect('/admin/articles?error=Permission+refusée');
        }

        // Charger les relations de l'article
        $article->categories = $articleRepository->getCategories($articleId);
        $article->tags = $articleRepository->getTags($articleId);
        $article->author = $userRepository->find($article->author_id);

        // Récupérer les catégories et tags disponibles
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->findAll();

        $tagRepository = new TagRepository();
        $tags = $tagRepository->findAll();

        $csrfToken = CsrfToken::generate();

        return $this->render('admin/edit-article', [
            'article' => $article,
            'categories' => $categories,
            'tags' => $tags,
            'csrf_token' => $csrfToken
        ]);
    }
}
