<?php

namespace App\Modules\Student\Schedule\Resources;

use App\Resources\BaseResource;
use Illuminate\Http\Request;

class SpecialityResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'speciality';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request),[
            'name' => $this->name,
        ]);
    }
}
