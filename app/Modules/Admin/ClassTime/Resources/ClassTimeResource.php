<?php

namespace App\Modules\Admin\ClassTime\Resources;

use App\Resources\BaseResource;
use Illuminate\Http\Request;

class ClassTimeResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'class_time';

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
            'time_start' => $this->time_start->format('H:i'),
            'time_end' => $this->time_end->format('H:i'),
        ]);
    }
}
