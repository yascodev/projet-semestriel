<?php

namespace App\Controllers\Admin\Articles;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\ArticleRepository;
use App\Repositories\UserRepository;
use App\Lib\Auth\Session;

class AuthorArticlesController extends AbstractController
{
    public function process(Request $request): Response
    {
        $authorId = (int) $request->getSlug('id');
        
        $userRepository = new UserRepository();
        $author = $userRepository->find($authorId);
        
        if (!$author) {
            Session::set('flash_error', "Auteur non trouvé.");
            return Response::redirect('/admin/articles');
        }

        $articleRepository = new ArticleRepository();
        $articles = $articleRepository->findBy(['author_id' => $authorId]);

        $articlesWithDetails = [];
        foreach ($articles as $article) {
            $categories = $articleRepository->getCategories($article->id);
            $categoryNames = array_map(fn($cat) => $cat->name, $categories);
            
            $articlesWithDetails[] = [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'status' => $article->status,
                'categories' => $categoryNames,
                'created_at' => $article->created_at
            ];
        }

        return $this->render('admin/articles/author', [
            'author' => $author,
            'articles' => $articlesWithDetails
        ]);
    }
}
