<?php

namespace App\Modules\Admin\Discipline\Services;

use App\Models\Discipline;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class DisciplineService extends BaseService
{
    public function __construct(Discipline $discipline)
    {
        $this->model = $discipline;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     * @return Discipline
     */
    public function create(array $attributes): Discipline
    {
        $discipline = $this->model;
        $discipline->fill($attributes);

        $discipline->save();
        $discipline->specialities()->attach($attributes['speciality_ids']);
        $discipline->refresh();

        return $discipline;
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
        $discipline = $this->find($id);

        $discipline->fill($attributes);
        $discipline->specialities()->sync($attributes['speciality_ids']);
        $discipline->save();

        return $discipline;
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        Cache::forget($this->model->getCacheKey($id));

        $discipline = $this->find($id);

        $discipline->specialities()->detach();

        return $this->find($id)->delete();
    }
}
