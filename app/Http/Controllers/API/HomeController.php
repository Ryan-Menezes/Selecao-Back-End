<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CommentService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(protected CommentService $service)
    {}

    public function index(Request $request)
    {
        $limit = $request->get('limit', 15);

        $comments = $this->service->findAllWithAuthorPaginate($limit);

        return $this->json($comments, wrapper: false);
    }
}
