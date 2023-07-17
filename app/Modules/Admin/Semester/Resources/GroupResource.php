<?php

namespace App\Modules\Admin\Semester\Resources;

use App\Resources\BaseResource;
use Illuminate\Http\Request;

class GroupResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'group';

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
            'education_type' => $this->education_type,
        ]);
    }
}
