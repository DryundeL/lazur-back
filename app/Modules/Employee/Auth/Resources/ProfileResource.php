<?php

namespace App\Modules\Employee\Auth\Resources;

use App\Resources\BaseResource;
use Illuminate\Http\Request;

class ProfileResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'profile';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'patronymic_name' => $this->patronymic_name,
            'email' => $this->email,
            'extended_user_id' => $this->extended_user_id,
            'extended_token' => $this->extended_token,
            'role' => $this->role,
        ]);
    }
}
