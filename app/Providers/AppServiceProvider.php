<?php

namespace App\Providers;

use App\Models\Comment;
use App\Observers\CommentObserver;
use App\Repositories\CommentHistoricRepositoryInterface;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\EloquentORM\CommentHistoricRepository;
use App\Repositories\EloquentORM\CommentRepository;
use App\Repositories\EloquentORM\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Comment::observe(CommentObserver::class);

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(CommentHistoricRepositoryInterface::class, CommentHistoricRepository::class);
    }
}
