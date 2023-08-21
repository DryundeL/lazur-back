<?php

namespace App\Modules\Employee\Journal\Resources;

use App\Resources\BaseResource;
use Illuminate\Http\Request;

class JournalDateResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'journal_date';

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
            'count' => $this->count,
        ]);
    }
}
