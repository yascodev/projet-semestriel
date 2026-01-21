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
use App\Entities\Article;

class CreateArticleActionController extends AbstractController
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

        $title = trim($request->getPost('title') ?? '');
        $excerpt = trim($request->getPost('excerpt') ?? '');
        $content = trim($request->getPost('content') ?? '');
        $action = $request->getPost('action') ?? 'draft';
        $categoryIds = $request->getPost('categories') ?? [];
        $tagIds = $request->getPost('tags') ?? [];

        // Validation basique
        if (empty($title) || empty($excerpt) || empty($content)) {
            return Response::redirect('/admin/articles/create?error=Tous+les+champs+obligatoires+doivent+être+remplis');
        }

        if (strlen($title) < 3 || strlen($title) > 255) {
            return Response::redirect('/admin/articles/create?error=Le+titre+doit+faire+entre+3+et+255+caractères');
        }

        if (strlen($excerpt) < 10 || strlen($excerpt) > 500) {
            return Response::redirect('/admin/articles/create?error=L\'extrait+doit+faire+entre+10+et+500+caractères');
        }

        if (strlen($content) < 10) {
            return Response::redirect('/admin/articles/create?error=Le+contenu+doit+faire+au+minimum+10+caractères');
        }

        // Créer l'article
        $article = new Article();
        $article->title = $title;
        $article->excerpt = $excerpt;
        $article->content = $content;
        $article->author_id = Session::get('user_id');
        $article->status = ($action === 'publish') ? 'published' : 'draft';
        $article->created_at = date('Y-m-d H:i:s');
        $article->updated_at = date('Y-m-d H:i:s');
        
        if ($article->status === 'published') {
            $article->published_at = date('Y-m-d H:i:s');
        }

        $article->generateSlug();

        $articleRepository = new ArticleRepository();
        $article->slug = $articleRepository->generateUniqueSlug($article->slug);
        
        // Insérer l'article
        $articleRepository->create($article);

        // Récupérer l'ID du nouvel article
        $newArticle = $articleRepository->findBySlug($article->slug);

        if (!$newArticle) {
            return Response::redirect('/admin/articles?error=Erreur+lors+de+la+création');
        }

        // Ajouter les catégories
        if (!empty($categoryIds)) {
            $categoryRepository = new CategoryRepository();
            foreach ($categoryIds as $catId) {
                $catId = (int) $catId;
                $category = $categoryRepository->find($catId);
                if ($category) {
                    $sql = "INSERT INTO article_category (article_id, category_id) VALUES (:article_id, :category_id)";
                    $stmt = $articleRepository->getConnexion()->prepare($sql);
                    $stmt->execute(['article_id' => $newArticle->id, 'category_id' => $catId]);
                }
            }
        }

        // Ajouter les tags
        if (!empty($tagIds)) {
            $tagRepository = new TagRepository();
            foreach ($tagIds as $tagId) {
                $tagId = (int) $tagId;
                $tag = $tagRepository->find($tagId);
                if ($tag) {
                    $sql = "INSERT INTO article_tag (article_id, tag_id) VALUES (:article_id, :tag_id)";
                    $stmt = $articleRepository->getConnexion()->prepare($sql);
                    $stmt->execute(['article_id' => $newArticle->id, 'tag_id' => $tagId]);
                }
            }
        }

        $success = $action === 'publish' ? 'created' : 'created';
        return Response::redirect('/admin/articles?success=' . $success);
    }
}
