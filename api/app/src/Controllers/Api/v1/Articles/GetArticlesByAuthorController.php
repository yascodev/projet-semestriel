<?php

namespace App\Controllers\Api\v1\Articles;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\ArticleRepository;
use App\Repositories\UserRepository;

class GetArticlesByAuthorController extends AbstractController {
    public function process(Request $request): Response
    {
        $authorId = (int) $request->getSlug('id');
        
        $userRepository = new UserRepository();
        $author = $userRepository->find($authorId);
        
        if (!$author) {
            return new Response(json_encode([
                'success' => false,
                'message' => 'Auteur non trouvé'
            ]), 404, ['Content-Type' => 'application/json']);
        }

        $articleRepository = new ArticleRepository();
        // On récupère les articles publiés de cet auteur
        $articles = $articleRepository->findBy(['author_id' => $authorId, 'status' => 'published']);
        
        return new Response(json_encode([
            'success' => true,
            'author' => [
                'id' => $author->id,
                'firstname' => $author->firstname,
                'lastname' => $author->lastname,
                'email' => $author->email
            ],
            'articles' => $articles
        ]), 200, ['Content-Type' => 'application/json']);
    }
}
