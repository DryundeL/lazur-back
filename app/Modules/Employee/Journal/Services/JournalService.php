<?php

namespace App\Modules\Employee\Journal\Services;

use App\Models\Journal;
use App\Services\BaseService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class JournalService extends BaseService
{
    public function __construct(Journal $journal)
    {
        $this->model = $journal;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     * @return Journal $model
     */
    public function create(array $attributes): Journal
    {
        $journal = $this->model;

        $journal->fill($attributes);
        $journal->journalDate()->associate($attributes['journal_date_id']);
        $journal->student()->associate($attributes['student_id']);
        $journal->save();

        Cache::put($journal->getCacheKey($journal->id), $journal, Carbon::now()->addMinutes(15));

        return $journal;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model $model
     */
    public function update(array $attributes, int $id): \Illuminate\Database\Eloquent\Model
    {
        $journal = $this->find($id);

        $journal->update($attributes);
        $journal->journalDate()->associate($attributes['journal_date_id']);
        $journal->student()->associate($attributes['student_id']);
        $journal->save();

        Cache::put($journal->getCacheKey($id), $journal, Carbon::now()->addMinutes(15));

        return $journal;
    }
}
