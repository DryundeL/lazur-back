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
     * @param Change $change
     * @param $attributes
     * @return void
     */
    private function createChangeDetail(Change $change, $attributes): void
    {
        foreach ($attributes['change_details'] as $changeInfo) {
            $changeDetails = new ChangeDetail();

            $changeDetails->change()->associate($change->id);
            $changeDetails->employee()->associate($changeInfo['employee_id']);
            $changeDetails->audience()->associate($changeInfo['audience_id']);

            $changeDetails->save();
        }
    }

    /**
     * Associates change with some models
     *
     * @param Change $change
     * @param $attributes
     * @return void
     */
    private function associateWithChange(Change $change, $attributes): void
    {
        $change->discipline()->associate($attributes['discipline_id']);
        $change->classTime()->associate($attributes['class_time_id']);
        $change->group()->associate($attributes['group_id']);
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
        $this->associateWithChange($change, $attributes);
        $change->save();

        $this->createChangeDetail($change, $attributes);

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
        $this->associateWithChange($change, $attributes);
        $change->changeDetails()->delete();
        $change->save();

        Cache::put($change->getCacheKey($id), $change, Carbon::now()->addMinutes(15));

        $this->createChangeDetail($change, $attributes);

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
