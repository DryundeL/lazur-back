<?php

namespace App\Modules\Student\Auth\Services;

use Illuminate\Support\Facades\Hash;
use App\Modules\Student\Auth\Resources\ProfileResource;
use App\Modules\Student\Models\Student;
use App\Services\BaseAuthService;

class AuthService extends BaseAuthService
{
    /**
     * Login user.
     *
     * @param array $attributes
     * @return array|bool
     */
    public function login(array $attributes): array | bool
    {
        $student = Student::where('email', $attributes['email'])->first();

        if (!$student || !Hash::check($attributes['password'], $student->password)) {
            return false;
        }

        $token = $student->createToken('token')->plainTextToken;

        $this->addToMatterMost($student);

        return [
            'student' => ProfileResource::make($student),
            'token' => $token,
            'extended_token' => $student->extended_token,
            'extended_user_id' => $student->extended_user_id,
        ];
    }
}
