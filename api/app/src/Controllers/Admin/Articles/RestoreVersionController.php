<?php

namespace App\Controllers\Admin\Articles;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleVersionRepository;
use App\Lib\Auth\Session;
use App\Lib\Auth\CsrfToken;

class RestoreVersionController extends AbstractController
{
    public function process(Request $request): Response
    {
        if (!Session::isAuthenticated()) {
            return Response::redirect('/login');
        }

        if ($request->getMethod() !== 'POST') {
            return Response::redirect('/admin/articles');
        }

        $token = $request->post('csrf_token');
        if (!CsrfToken::validate($token)) {
            Session::set('flash_error', "Token CSRF invalide.");
            return Response::redirect('/admin/articles');
        }

        $versionId = (int) $request->post('version_id');
        $versionRepository = new ArticleVersionRepository();
        $version = $versionRepository->find($versionId);

        if (!$version) {
            Session::set('flash_error', "Version non trouvée.");
            return Response::redirect('/admin/articles');
        }

        $articleRepository = new ArticleRepository();
        $article = $articleRepository->find($version->article_id);

        if (!$article) {
            Session::set('flash_error', "Article associé non trouvé.");
            return Response::redirect('/admin/articles');
        }

        // Vérifier les permissions
        if (!$this->canManageAllArticles() && $article->author_id !== Session::get('user_id')) {
            Session::set('flash_error', "Vous n'avez pas l'autorisation de restaurer cet article.");
            return Response::redirect('/admin/articles');
        }

        // Avant de restaurer, on sauvegarde la version actuelle de l'article si ce n'est pas déjà fait
        // Note: Le repository d'article le fait déjà dans sa méthode update()

        // Restaurer les données
        $article->title = $version->title;
        $article->content = $version->content;
        $article->updated_at = (new \DateTime())->format('Y-m-d H:i:s');
        
        // On regénère le slug si le titre a changé pour rester cohérent, 
        // ou on garde l'actuel ? Généralement on garde le slug pour le SEO mais si on restaure une version très ancienne...
        // Ici on va garder le slug actuel pour éviter de casser les liens, sauf si spécifié autrement.
        
        $articleRepository->update($article);

        Session::set('flash_success', "La version du " . $version->created_at . " a été restaurée avec succès. Une nouvelle version de sauvegarde a été créée.");
        
        return Response::redirect('/admin/articles/versions?id=' . $article->id);
    }
}
