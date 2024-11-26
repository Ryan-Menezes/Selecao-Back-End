<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\CommentHistoric;

class CommentObserver
{
    public function created(Comment $comment)
    {
        CommentHistoric::query()->create([
            'comment_id' => $comment->id,
            'content' => $comment->content,
        ]);
    }

    public function updated(Comment $comment)
    {
        if ($comment->wasChanged('content')) {
            CommentHistoric::query()->create([
                'comment_id' => $comment->id,
                'content' => $comment->content,
            ]);
        }
    }

    public function deleted(Comment $comment)
    {
        //
    }

    public function restored(Comment $comment)
    {
        //
    }

    public function forceDeleted(Comment $comment)
    {
        //
    }
}
