<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Lib\Auth\Session;
use App\Lib\Auth\CsrfToken;
use App\Repositories\ArticleRepository;
use App\Repositories\UserRepository;

class DeleteArticleController extends AbstractController
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

        // Seul l'admin et les propriétaires peuvent supprimer
        if ($currentUser->role !== 'admin' && $article->author_id !== $currentUserId) {
            return Response::redirect('/admin/articles?error=Permission+refusée');
        }

        // Supprimer les associations (catégories et tags)
        $connection = $articleRepository->getConnexion();
        
        $deleteCategorySql = "DELETE FROM article_category WHERE article_id = :article_id";
        $deleteCategoryStmt = $connection->prepare($deleteCategorySql);
        $deleteCategoryStmt->execute(['article_id' => $articleId]);

        $deleteTagSql = "DELETE FROM article_tag WHERE article_id = :article_id";
        $deleteTagStmt = $connection->prepare($deleteTagSql);
        $deleteTagStmt->execute(['article_id' => $articleId]);

        // Supprimer les versions de l'article
        $deleteVersionSql = "DELETE FROM article_version WHERE article_id = :article_id";
        $deleteVersionStmt = $connection->prepare($deleteVersionSql);
        $deleteVersionStmt->execute(['article_id' => $articleId]);

        // Supprimer l'article
        $articleRepository->delete($article);

        return Response::redirect('/admin/articles?success=deleted');
    }
}
