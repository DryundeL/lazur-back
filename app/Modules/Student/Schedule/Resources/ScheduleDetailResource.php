<?php

namespace App\Modules\Student\Schedule\Resources;

use App\Modules\Admin\Audience\Resources\AudienceResource;
use App\Resources\BaseResource;
use Illuminate\Http\Request;

class ScheduleDetailResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'schedule_detail';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'employee' => EmployeeResource::make($this->employee),
            'audience' => AudienceResource::make($this->audience),
        ]);
    }
}
