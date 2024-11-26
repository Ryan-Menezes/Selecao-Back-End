<?php

use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\Manage\AuthController;
use App\Http\Controllers\API\Manage\CommentController;
use App\Http\Controllers\API\Manage\ProfileController;
use App\Http\Controllers\API\Manage\UserController;
use App\Models\Comment;
use App\Models\CommentHistoric;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('api.home');

Route::group([
    'prefix' => '/manage',
], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.manage.auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('api.manage.auth.register');

    Route::group([
        'prefix' => '/',
        'middleware' => 'auth:sanctum',
    ], function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.manage.auth.logout');

        Route::prefix('/me')->group(function () {
            Route::get('/', [ProfileController::class, 'me'])->name('api.manage.profile.me');
            Route::put('/', [ProfileController::class, 'update'])->name('api.manage.profile.update');
        });

        Route::prefix('/users')->group(function () {
            Route::get('/', [UserController::class, 'index'])
                ->name('api.manage.users.index')
                ->can('viewAny', User::class);

            Route::get('/{user}', [UserController::class, 'show'])
                ->name('api.manage.users.show')
                ->can('view', 'user');

            Route::post('/', [UserController::class, 'store'])
                ->name('api.manage.users.store')
                ->can('create', User::class);

            Route::put('/{user}', [UserController::class, 'update'])
                ->name('api.manage.users.update')
                ->can('update', 'user');

            Route::delete('/{user}', [UserController::class, 'destroy'])
                ->name('api.manage.users.destroy')
                ->can('delete', 'user');
        });

        Route::prefix('/comments')->group(function () {
            Route::get('/', [CommentController::class, 'index'])
                ->name('api.manage.comments.index')
                ->can('viewAny', Comment::class);

            Route::get('/{comment}', [CommentController::class, 'show'])
                ->name('api.manage.comments.show')
                ->can('view', 'comment');

            Route::post('/', [CommentController::class, 'store'])
                ->name('api.manage.comments.store')
                ->can('create', Comment::class);

            Route::put('/{comment}', [CommentController::class, 'update'])
                ->name('api.manage.comments.update')
                ->can('update', 'comment');

            Route::delete('/{comment}', [CommentController::class, 'destroy'])
                ->name('api.manage.comments.destroy')
                ->can('delete', 'comment');

            Route::get('/{comment}/historics', [CommentController::class, 'historics'])
                ->name('api.manage.comments.historics')
                ->can('viewAny', CommentHistoric::class);
        });
    });
});
