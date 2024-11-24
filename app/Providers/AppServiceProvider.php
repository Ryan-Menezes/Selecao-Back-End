<?php

namespace App\Providers;

use App\Repositories\CommentRepositoryInterface;
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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
    }
}
