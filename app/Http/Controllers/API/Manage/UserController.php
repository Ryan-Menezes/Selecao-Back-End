<?php

namespace App\Http\Controllers\API\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {}

    public function index(Request $request)
    {
        $limit = $request->get('limit', 15);

        $users = $this->userService->findAllPaginate($limit);

        return $this->json($users, wrapper: false);
    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $user = $this->userService->create($data);

        return $this->json($user, Response::HTTP_CREATED);
    }

    public function show(User $user)
    {
        return $this->json($user->toArray());
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();

        $this->userService->update($user->id, $data);

        $user = $user->fresh();

        return $this->json($user->toArray());
    }

    public function destroy(User $user)
    {
        $this->userService->delete($user->id);

        return $this->success('User deleted successfully');
    }
}
