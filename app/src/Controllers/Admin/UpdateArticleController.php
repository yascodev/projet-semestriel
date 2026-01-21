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

class UpdateArticleController extends AbstractController
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

        // Les auteurs ne peuvent éditer que leurs articles
        if ($currentUser->role === 'author' && $article->author_id !== $currentUserId) {
            return Response::redirect('/admin/articles?error=Permission+refusée');
        }

        // Récupérer les données POST
        $title = trim($request->getPost('title') ?? '');
        $excerpt = trim($request->getPost('excerpt') ?? '');
        $content = trim($request->getPost('content') ?? '');
        $status = $request->getPost('status') ?? 'draft';
        $categoryIds = $request->getPost('categories') ?? [];
        $tagIds = $request->getPost('tags') ?? [];

        // Validation basique
        if (empty($title) || empty($excerpt) || empty($content)) {
            return Response::redirect('/admin/articles/' . $articleId . '/edit?error=Tous+les+champs+obligatoires+doivent+être+remplis');
        }

        if (strlen($title) < 3 || strlen($title) > 255) {
            return Response::redirect('/admin/articles/' . $articleId . '/edit?error=Le+titre+doit+faire+entre+3+et+255+caractères');
        }

        if (strlen($excerpt) < 10 || strlen($excerpt) > 500) {
            return Response::redirect('/admin/articles/' . $articleId . '/edit?error=L\'extrait+doit+faire+entre+10+et+500+caractères');
        }

        if (strlen($content) < 10) {
            return Response::redirect('/admin/articles/' . $articleId . '/edit?error=Le+contenu+doit+faire+au+minimum+10+caractères');
        }

        // Mettre à jour l'article
        $article->title = $title;
        $article->excerpt = $excerpt;
        $article->content = $content;
        $article->status = $status;
        $article->updated_at = date('Y-m-d H:i:s');

        // Si on passe en published et que ce n'était pas publié avant
        if ($status === 'published' && $article->status !== 'published') {
            $article->published_at = date('Y-m-d H:i:s');
        }

        // Régénérer le slug si le titre a changé
        $article->generateSlug();
        $article->slug = $articleRepository->generateUniqueSlug($article->slug, $article->id);

        // Sauvegarder l'article
        $articleRepository->update($article);

        // Mettre à jour les catégories
        $this->updateCategories($articleId, $categoryIds, $articleRepository);

        // Mettre à jour les tags
        $this->updateTags($articleId, $tagIds, $articleRepository);

        return Response::redirect('/admin/articles?success=updated');
    }

    private function updateCategories(int $articleId, array $categoryIds, ArticleRepository $articleRepository): void
    {
        $connection = $articleRepository->getConnexion();

        // Supprimer toutes les associations existantes
        $deleteSql = "DELETE FROM article_category WHERE article_id = :article_id";
        $deleteStmt = $connection->prepare($deleteSql);
        $deleteStmt->execute(['article_id' => $articleId]);

        // Ajouter les nouvelles associations
        if (!empty($categoryIds)) {
            $categoryRepository = new CategoryRepository();
            foreach ($categoryIds as $catId) {
                $catId = (int) $catId;
                $category = $categoryRepository->find($catId);
                if ($category) {
                    $insertSql = "INSERT INTO article_category (article_id, category_id) VALUES (:article_id, :category_id)";
                    $insertStmt = $connection->prepare($insertSql);
                    $insertStmt->execute(['article_id' => $articleId, 'category_id' => $catId]);
                }
            }
        }
    }

    private function updateTags(int $articleId, array $tagIds, ArticleRepository $articleRepository): void
    {
        $connection = $articleRepository->getConnexion();

        // Supprimer toutes les associations existantes
        $deleteSql = "DELETE FROM article_tag WHERE article_id = :article_id";
        $deleteStmt = $connection->prepare($deleteSql);
        $deleteStmt->execute(['article_id' => $articleId]);

        // Ajouter les nouvelles associations
        if (!empty($tagIds)) {
            $tagRepository = new TagRepository();
            foreach ($tagIds as $tagId) {
                $tagId = (int) $tagId;
                $tag = $tagRepository->find($tagId);
                if ($tag) {
                    $insertSql = "INSERT INTO article_tag (article_id, tag_id) VALUES (:article_id, :tag_id)";
                    $insertStmt = $connection->prepare($insertSql);
                    $insertStmt->execute(['article_id' => $articleId, 'tag_id' => $tagId]);
                }
            }
        }
    }
}
