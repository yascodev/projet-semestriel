<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Lib\Auth\Session;
use App\Lib\Auth\CsrfToken;
use App\Repositories\ArticleRepository;
use App\Repositories\UserRepository;

class PublishArticleController extends AbstractController
{
    public function process(Request $request): Response
    {
        // Vérifier l'authentification
        if (!Session::isAuthenticated()) {
            return Response::redirect('/login');
        }

        // Vérifier le token CSRF
        $csrfToken = $request->getPost('csrf_token');
        if (!$csrfToken || !CsrfToken::validate($csrfToken)) {
            return Response::redirect('/admin/articles?error=Erreur+CSRF');
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

        // Les auteurs ne peuvent publier que leurs articles
        if ($currentUser->role === 'author' && $article->author_id !== $currentUserId) {
            return Response::redirect('/admin/articles?error=Permission+refusée');
        }

        // Publier l'article
        $article->status = 'published';
        $article->published_at = date('Y-m-d H:i:s');
        $article->updated_at = date('Y-m-d H:i:s');

        $articleRepository->update($article);

        return Response::redirect('/admin/articles?success=published');
    }
}
