<?php

namespace App\Modules\Admin\Audience\Resources;

use App\Resources\BaseResource;
use Illuminate\Http\Request;

class AudienceResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'auditorium';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request),[
            'corpus' => $this->corpus,
            'cabinet_number' => $this->cabinet_number,
        ]);
    }
}
