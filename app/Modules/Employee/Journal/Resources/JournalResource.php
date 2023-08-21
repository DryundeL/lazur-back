<?php

namespace App\Modules\Employee\Journal\Resources;

use App\Resources\BaseResource;
use Illuminate\Http\Request;

class JournalResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'journal';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'journal_date' => JournalDateResource::make($this->journalDate),
            'student' => StudentResource::make($this->student),
            'mark' => $this->mark,
        ]);
    }
}
