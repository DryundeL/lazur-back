<?php

namespace App\Modules\Employee\Auth\Services;

use App\Modules\Employee\Auth\Resources\ProfileResource;
use App\Modules\Employee\Models\Employee;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AuthService extends BaseService
{
    /**
     * Login user.
     *
     * @param array $attributes
     * @return array|bool
     */
    public function login(array $attributes): array | bool
    {
        $employee = Employee::where('email', $attributes['email'])->first();

        if (!$employee || !Hash::check($attributes['password'], $employee->password)) {
            return false;
        }

        $token = $employee->createToken('token')->plainTextToken;

        $this->addToMatterMost($employee);

        return [
            'employee' => ProfileResource::make($employee),
            'token' => $token,
            'extended_token' => $employee->extended_token,
            'extended_user_id' => $employee->extended_user_id,
        ];
    }

    /**
     * Logout admin.
     *
     */
    public function logout(): void
    {
        $employee = Auth::user();
        $employee->currentAccessToken()->delete();
    }
}
