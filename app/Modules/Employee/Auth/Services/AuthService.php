<?php

namespace App\Modules\Employee\Auth\Services;

use Illuminate\Support\Facades\Hash;
use App\Modules\Employee\Auth\Resources\ProfileResource;
use App\Modules\Employee\Models\Employee;
use App\Services\BaseService;
use App\Traits\Authorizable;

class AuthService extends BaseService
{
    use Authorizable;

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
}
