<?php

namespace App\Controllers\Api\v1\Articles;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\ArticleVersionRepository;
use App\Lib\Auth\Session;

class GetArticleVersionsController extends AbstractController {
    public function process(Request $request): Response
    {
        if (!Session::isAuthenticated()) {
            return new Response(json_encode(["success" => false, "message" => "Unauthorized"]), 403, ["Content-Type" => "application/json"]);
        }
        $articleId = (int)($request->getUrlParams()['id'] ?? 0);
        $repo = new ArticleVersionRepository();
        $versions = $repo->findByArticle($articleId);
        if (!$versions || count($versions) === 0) {
            return new Response(json_encode(["success" => true, "data" => []]), 200, ["Content-Type" => "application/json"]);
        }
        return new Response(json_encode(["success" => true, "data" => $versions]), 200, ["Content-Type" => "application/json"]);
    }
}

?>
