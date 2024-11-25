<?php

namespace App\Http\Controllers\API\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function me(Request $request)
    {
        $user = $request->user()->toArray();

        return $this->json($user);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        $this->userService->update($user->id, $data);

        $user = $user->fresh();

        return $this->json($user->toArray());
    }
}
