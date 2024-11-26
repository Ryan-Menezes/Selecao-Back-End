<?php

namespace App\Http\Controllers\API\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function login(AuthLoginRequest $request)
    {
        $token = $this->userService->login($request->email, $request->password);

        if (!$token) {
            return $this->error('Invalid credentials', Response::HTTP_UNAUTHORIZED);
        }

        return $this->json(['token' => $token], wrapper: false);
    }

    public function logout(Request $request)
    {
        $id = $request->user()->id;

        $this->userService->logout($id);

        return $this->success('Logged out successfully');
    }

    public function register(AuthRegisterRequest $request)
    {
        $data = $request->validated();

        $user = $this->userService->create($data);
        $token = $this->userService->createTokenFor($user['id']);

        return $this->json(['user' => $user, 'token' => $token], Response::HTTP_CREATED, false);
    }
}
