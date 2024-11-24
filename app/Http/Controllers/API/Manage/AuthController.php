<?php

namespace App\Http\Controllers\API\Manage;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

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

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'string', 'max:191', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'max:191'],
        ]);

        $user = $this->userService->create($data);
        $token = $this->userService->createTokenFor($user['id']);

        return $this->json(['user' => $user, 'token' => $token], wrapper: false);
    }

    public function me(Request $request)
    {
        $user = $request->user()->toArray();

        return $this->json($user);
    }
}
