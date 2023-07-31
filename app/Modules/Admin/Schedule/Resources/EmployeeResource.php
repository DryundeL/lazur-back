<?php

namespace App\Modules\Admin\Schedule\Resources;

use App\Resources\BaseResource;
use Illuminate\Http\Request;

class EmployeeResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'employee';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request),[
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'patronymic_name' => $this->patronymic_name,
            'role' => $this->role,
        ]);
    }
}
