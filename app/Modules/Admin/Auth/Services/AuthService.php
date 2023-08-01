<?php

namespace App\Modules\Admin\Auth\Services;

use App\Models\Employee;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use App\Modules\Admin\Models\Admin;
use App\Services\BaseService;
use App\Traits\Authorizable;

class AuthService extends BaseService
{
    use Authorizable;

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

    /**
     * Login user.
     *
     * @param  array $attributes
     * @return array
     */
    public function generateTokenAuthForAdmin(array $attributes): array
    {
        switch ($attributes['user_type']) {
            case 'employee':
                $user = Employee::find($attributes['id']);
                break;
            case 'student':
                $user = Student::find($attributes['id']);
                break;
        }

        if (!$user) {
            return ['error' => 'Такого пользователя не существует'];
        }

        return ['token' => $user->createToken('token')->plainTextToken];
    }
}
