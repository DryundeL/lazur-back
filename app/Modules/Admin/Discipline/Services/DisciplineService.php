<?php

namespace App\Modules\Admin\Discipline\Services;

use App\Models\Discipline;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;

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
        $discipline->speciality()->associate($attributes['speciality_id']);
        $discipline->save();

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
        $discipline->speciality()->associate($attributes['speciality_id']);
        $discipline->save();

        return $discipline;
    }
}
