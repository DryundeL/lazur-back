<?php

namespace App\Modules\Admin\Change\Resources;

use App\Resources\BaseResource;
use Illuminate\Http\Request;

class ChangeResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'change';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'date' => $this->date,
            'group' => GroupResource::make($this->group),
            'class_time' => ClassTimeResource::make($this->classTime),
            'discipline' => DisciplineResource::make($this->discipline),
            'change_details' => ChangeDetailResource::collection($this->changeDetails),
        ]);
    }
}
