<?php

namespace App\Modules\Student\Auth\Controllers;

use App\Http\Controllers\BaseController as Controller;
use App\Modules\Student\Auth\Requests\LoginAuthRequest;
use App\Modules\Student\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Login user.
     *
     * @param LoginAuthRequest $request
     * @param AuthService $authService
     * @return JsonResponse
     */
    public function login(LoginAuthRequest $request, AuthService $authService): JsonResponse
    {
        $student = $authService->login($request->validated());

        if (!$student) {
            return $this->sendErrorResponse([], 401);
        }

        return $this->sendResponse(['student' => $student]);
    }

    /**
     * Logout user.
     *
     * @param AuthService $authService
     * @return JsonResponse
     */
    public function logout(AuthService $authService): JsonResponse
    {
        $authService->logout();

        return $this->sendResponse();
    }
}
