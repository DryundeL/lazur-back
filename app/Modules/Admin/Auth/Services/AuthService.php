<?php

namespace App\Modules\Admin\Auth\Services;

use Illuminate\Support\Facades\Hash;
use App\Services\BaseAuthService;
use App\Modules\Admin\Models\Admin;

class AuthService extends BaseAuthService
{
    /**
     * Login user.
     *
     * @param  array $attributes
     * @return mixed
     */
    public function login(array $attributes)
    {
        $admin = Admin::where('email', $attributes['email'])->first();

        if (!$admin || !Hash::check($attributes['password'], $admin->password)) {
            return false;
        }

        $token = $admin->createToken('token')->plainTextToken;

        return $token;
    }
}