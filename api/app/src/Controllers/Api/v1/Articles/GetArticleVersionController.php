<?php
namespace App\Controllers\Api\v1\Articles;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Repositories\ArticleVersionRepository;
use App\Lib\Auth\Session;

class GetArticleVersionController extends AbstractController {
    public function process(Request $request): Response
    {
        if (!Session::isAuthenticated()) {
            return new Response(json_encode(["success" => false, "message" => "Unauthorized"]), 403, ["Content-Type" => "application/json"]);
        }

        $versionId = (int)($request->getUrlParams()["versionId"] ?? 0);
        $repo = new ArticleVersionRepository();
        $version = $repo->find($versionId);

        if (!$version) {
            return new Response(json_encode(["success" => false, "message" => "Version non trouvée"]), 404, ["Content-Type" => "application/json"]);
        }

        return new Response(json_encode(["success" => true, "data" => $version]), 200, ["Content-Type" => "application/json"]);
    }
}
