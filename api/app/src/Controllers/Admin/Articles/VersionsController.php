<?php

namespace App\Controllers\Admin\Articles;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleVersionRepository;
use App\Repositories\UserRepository;
use App\Lib\Auth\Session;

class VersionsController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (!Session::isAuthenticated()) {
            return Response::redirect('/login');
        }

        $articleId = (int) ($request->getUrlParams()['id'] ?? 0);
        if (!$articleId) {
            return Response::redirect('/admin/articles');
        }

        $articleRepository = new ArticleRepository();
        $article = $articleRepository->find($articleId);

        if (!$article) {
            Session::set('flash_error', "Article non trouvé.");
            return Response::redirect('/admin/articles');
        }

        // Vérifier les permissions (même logique que ArticlesController)
        if (!$this->canManageAllArticles() && $article->author_id !== Session::get('user_id')) {
            return Response::redirect('/admin/articles');
        }

        $versionRepository = new ArticleVersionRepository();
        $versions = $versionRepository->findByArticle($articleId);

        // Trier par date décroissante (plus récente en premier)
        usort($versions, function($a, $b) {
            return strcmp($b->created_at, $a->created_at);
        });

        $userRepository = new UserRepository();
        $versionsWithDetails = [];
        foreach ($versions as $version) {
            $author = $userRepository->find($version->author_id);
            $versionsWithDetails[] = [
                'id' => $version->id,
                'title' => $version->title,
                'author_name' => $author ? ($author->firstname . ' ' . $author->lastname) : 'Inconnu',
                'created_at' => $version->created_at,
                'updated_at' => $version->updated_at
            ];
        }

        $flashSuccess = Session::get('flash_success');
        $flashError = Session::get('flash_error');
        Session::remove('flash_success');
        Session::remove('flash_error');

        return $this->render('admin/articles/versions', [
            'article' => $article,
            'versions' => $versionsWithDetails,
            'flash_success' => $flashSuccess,
            'flash_error' => $flashError
        ]);
    }
}
