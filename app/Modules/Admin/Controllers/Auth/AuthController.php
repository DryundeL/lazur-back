<?php

namespace App\Modules\Admin\Controllers\Auth;

use App\Modules\Admin\Requests\LoginAuthRequest;
use App\Modules\Admin\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Response;

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
