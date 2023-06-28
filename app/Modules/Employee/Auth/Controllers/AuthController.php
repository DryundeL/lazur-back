<?php

namespace App\Modules\Employee\Auth\Controllers;

use App\Http\Controllers\BaseController as Controller;
use App\Modules\Employee\Auth\Requests\LoginAuthRequest;
use App\Modules\Employee\Auth\Services\AuthService;
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
        $employee = $authService->login($request->validated());

        if (!$employee) {
            return $this->sendErrorResponse([], 401);
        }

        return $this->sendResponse(['employee' => $employee]);
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
