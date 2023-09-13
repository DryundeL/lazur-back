<?php

namespace App\Modules\Student\Schedule\Resources;

use App\Modules\Admin\Group\Resources\GroupResource;
use App\Resources\BaseResource;
use Illuminate\Http\Request;

class SemesterResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'semester';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request),[
            'number' => $this->number,
            'start_date' => $this->start_date->format('d.m.Y'),
            'finish_date' => $this->finish_date->format('d.m.Y'),
        ]);
    }
}
