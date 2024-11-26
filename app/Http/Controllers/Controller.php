<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function error(string $message = 'Não encontrado', int $status = Response::HTTP_NOT_FOUND): JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }

    protected function success(string $message, int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }

    protected function json(array $data, int $status = Response::HTTP_OK, bool $wrapper = true): JsonResponse
    {
        if (!$wrapper) return response()->json($data, $status);

        return response()->json(['data' => $data], $status);
    }
}
