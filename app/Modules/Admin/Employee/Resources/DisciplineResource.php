<?php

namespace App\Modules\Admin\Employee\Resources;

use App\Resources\BaseResource;
use Illuminate\Http\Request;
use App\Modules\Admin\Discipline\Resources\SpecialityResource;

class DisciplineResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'discipline';

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
            'hours' => $this->hours,
            'speciality' => SpecialityResource::make($this->speciality),
        ]);
    }
}
