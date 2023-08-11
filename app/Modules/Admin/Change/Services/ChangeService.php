<?php

namespace App\Modules\Admin\Change\Services;

use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Models\Change;
use App\Models\ChangeDetail;

class ChangeService extends BaseService
{
    public function __construct(Change $change)
    {
        $this->model = $change;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     * @return Change $model
     */
    public function create(array $attributes): Change
    {
        $change = $this->model;

        $change->fill($attributes);
        $change->discipline()->associate($attributes['discipline_id']);
        $change->classTime()->associate($attributes['class_time_id']);
        $change->group()->associate($attributes['group_id']);
        $change->save();

        foreach ($attributes['change_details'] as $changeDetail) {
            $changeDetails = new ChangeDetail();

            $changeDetails->change()->associate($change->id);
            $changeDetails->employee()->associate($changeDetail['employee_id']);
            $changeDetails->audience()->associate($changeDetail['audience_id']);

            $changeDetails->save();
        }

        Cache::put($change->getCacheKey($change->id), $change, Carbon::now()->addMinutes(15));

        return $change;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $attributes
     * @param int $id
     * @return Model
     */
    public function update(array $attributes, int $id): Model
    {
        $change = $this->find($id);

        $change->update($attributes);
        $change->discipline()->associate($attributes['discipline_id']);
        $change->classTime()->associate($attributes['class_time_id']);
        $change->group()->associate($attributes['group_id']);
        $change->changeDetails()->delete();
        $change->save();

        Cache::put($change->getCacheKey($id), $change, Carbon::now()->addMinutes(15));

        foreach ($attributes['change_details'] as $changeDetail) {
            $changeDetails = new ChangeDetail();

            $changeDetails->change()->associate($change->id);
            $changeDetails->employee()->associate($changeDetail['employee_id']);
            $changeDetails->audience()->associate($changeDetail['audience_id']);

            $changeDetails->save();
        }

        return $change;
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $change = $this->find($id);
        $change->changeDetails()->delete();

        Cache::forget($this->model->getCacheKey($id));

        return $change->delete();
    }
}
