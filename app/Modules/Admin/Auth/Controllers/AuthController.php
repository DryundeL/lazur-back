<?php

namespace App\Modules\Admin\Auth\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController as Controller;
use App\Modules\Admin\Auth\Requests\LoginAuthRequest;
use App\Modules\Admin\Auth\Services\AuthService;

class AuthController extends Controller
{
    /**
     * Login user.
     *
     * @param LoginAuthRequest $request
     * @param AuthService $authService
     * @return JsonResponse
     */
    public function login(LoginAuthRequest $request, AuthService $authService)
    {
        $token = $authService->login($request->validated());

        if (!$token) {
            return $this->sendErrorResponse([], 401);
        }

        return $this->sendResponse(['token' => $token]);
    }

    /**
     * Logout user.
     *
     * @param AuthService $authService
     * @return JsonResponse
     */
    public function logout(AuthService $authService)
    {
        $authService->logout();

        return $this->sendResponse();
    }
}
