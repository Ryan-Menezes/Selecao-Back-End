<?php

namespace App\Http\Controllers\API\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(protected UserService $service)
    {}

    public function index()
    {
        $users = $this->service->findAll();

        return $this->json($users);
    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $user = $this->service->create($data);

        return $this->json($user, Response::HTTP_CREATED);
    }

    public function show(User $user)
    {
        return $this->json($user->toArray());
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();

        $this->service->update($user->id, $data);

        return $this->json($user->toArray());
    }

    public function destroy(User $user)
    {
        $this->service->delete($user->id);

        return $this->success('User deleted successfully');
    }
}
