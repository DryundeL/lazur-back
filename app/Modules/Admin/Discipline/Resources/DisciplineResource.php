<?php

namespace App\Modules\Admin\Discipline\Resources;

use App\Resources\BaseResource;
use Illuminate\Http\Request;

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
