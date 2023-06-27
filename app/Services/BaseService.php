<?php

namespace App\Services;

class BaseService
{
    protected $model;

    public function __construct($model = null)
   {
       $this->model = $model;
   }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     * @return mixed|null $model
     */
    public function create(array $attributes)
    {
        $model = $this->model;

        $model->fill($attributes);
        $model->save();
        $model->refresh();

        return $model;
    }

    /**
     * Find the specified resource in storage.
     *
     * @param int $id
     * @return mixed $model
     */
    public function find(int $id)
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $attributes
     * @param int $id
     * @return mixed
     */
    public function update(array $attributes, int $id)
    {
        $model = $this->find($id);
        $model->update($attributes);
        $model->refresh();

        return $model;
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        return $this->find($id)->delete();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param array $ids
     * @return mixed
     */
    public function massDestroy(array $ids)
    {
        return $this->model->destroy($ids);
    }

    /**
     * Prepare validated data with nullable fields.
     *
     * @param  array  $validated
     * @param  array  $nullableFields
     * @return array
     */
    protected function prepareUpdateValidated($validated, $nullableFields)
    {
        foreach ($nullableFields as $nullableField) {
            if (!isset($validated[$nullableField])) {
                $validated[$nullableField] = null;
            }
        }

        return $validated;
    }
}
