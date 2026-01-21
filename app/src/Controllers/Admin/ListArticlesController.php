<?php

namespace App\Controllers\Admin;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Lib\Auth\Session;
use App\Lib\Auth\CsrfToken;
use App\Repositories\ArticleRepository;
use App\Repositories\UserRepository;

class ListArticlesController extends AbstractController
{
    public function process(Request $request): Response
    {
        // Vérifier l'authentification
        if (!Session::isAuthenticated()) {
            return Response::redirect('/login');
        }

        $userRepository = new UserRepository();
        $currentUser = $userRepository->find(Session::get('user_id'));
        
        if (!$currentUser) {
            return Response::redirect('/login');
        }

        // Récupérer les paramètres de filtrage
        $status = $request->getQuery('status') ?? '';
        $page = (int) ($request->getQuery('page') ?? 1);
        $perPage = 10;

        $articleRepository = new ArticleRepository();
        $articles = [];
        $totalArticles = 0;

        // Appliquer les filtres selon le rôle
        if ($currentUser->role === 'admin') {
            // Les admins voient tous les articles
            if ($status) {
                $articles = $articleRepository->findByStatus($status);
            } else {
                $articles = $articleRepository->findAll();
            }
        } else if ($currentUser->role === 'editor' || $currentUser->role === 'author') {
            // Les éditeurs et auteurs ne voient que leurs articles
            $userArticles = $articleRepository->findByAuthor($currentUser->id);
            
            if ($status) {
                $articles = array_filter($userArticles, fn($article) => $article->status === $status);
            } else {
                $articles = $userArticles;
            }
        }

        $totalArticles = count($articles);
        
        // Pagination
        $offset = ($page - 1) * $perPage;
        $articles = array_slice($articles, $offset, $perPage);
        $totalPages = ceil($totalArticles / $perPage);

        // Charger les informations complémentaires pour chaque article
        foreach ($articles as &$article) {
            $article->author = $userRepository->find($article->author_id);
            $article->categories = $articleRepository->getCategories($article->id);
            $article->tags = $articleRepository->getTags($article->id);
        }

        $csrfToken = CsrfToken::generate();

        return $this->render('admin/articles', [
            'articles' => $articles,
            'currentUser' => $currentUser,
            'status' => $status,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalArticles' => $totalArticles,
            'perPage' => $perPage,
            'csrf_token' => $csrfToken
        ]);
    }
}
