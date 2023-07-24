<?php

namespace App\Modules\Admin\Holiday\Resources;

use App\Resources\BaseResource;
use Illuminate\Http\Request;

class HolidayResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'holiday';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'date' => $this->date->format('d.m.Y'),
            'is_shortened' => $this->is_shortened,
        ]);
    }
}
