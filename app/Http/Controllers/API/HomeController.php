<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CommentService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(protected CommentService $commentService)
    {}

    public function index(Request $request)
    {
        $limit = $request->get('limit', 15);

        $comments = $this->commentService->findAllWithAuthorPaginate($limit);

        return $this->json($comments, wrapper: false);
    }
}
