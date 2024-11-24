<?php

namespace App\Http\Controllers\API\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function __construct(protected CommentService $service)
    {}

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->is_admin) {
            $comments = $this->service->findAll();
        } else {
            $comments = $this->service->findByUserId($user->id);
        }

        return $this->json($comments);
    }

    public function store(CommentStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $comment = $this->service->create($data);

        return $this->json($comment, Response::HTTP_CREATED);
    }

    public function show(Comment $comment)
    {
        return $this->json($comment->toArray());
    }

    public function update(CommentUpdateRequest $request, Comment $comment)
    {
        $data = $request->validated();

        $this->service->update($comment->id, $data);

        return $this->json($comment->toArray());
    }

    public function destroy(Comment $comment)
    {
        $this->service->delete($comment->id);

        return $this->success('Comment deleted successfully');
    }
}
