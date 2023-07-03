<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BaseService
{
    protected $model;

    public function __construct($model = null)
    {
        $this->model = $model;
    }

    /**
     * Returns array with filtered data and meta
     *
     * @param array $options
     * @param string|null $columnForQuery
     * @param bool|$areAllObjects
     * @return array
     */
    public function search(array $options, string $columnForQuery = null, bool $areAllObjects = false): array
    {
        $filters = array_diff_key($options, array_flip([
            'sort_by',
            'sort_dir',
            'date_range',
            'paginated',
            'query',
        ]));

        $dbSubQuery = $this->model::select();

        $columns = $this->model->getFillable();
        $tableName = $this->model->getTable();

        if (!$areAllObjects) {
            if (isset($columns['is_active'])) {
                $dbSubQuery->where('is_active', true);
            }

            if (isset($columns['is_approved'])) {
                $dbSubQuery->where('is_approved', true);
            }
        }

        if (isset($options['query']) and $columnForQuery) {
            $caseLoweredQuery = '%' . Str::lower($options['query']) . '%';

            if ($columnForQuery == 'FIO') {
                $dbSubQuery->where(function($query) use ($caseLoweredQuery) {
                    $query->orWhereRaw("lower(concat(last_name, ' ', first_name, ' ', patronymic_name)) LIKE ?", [$caseLoweredQuery]);
                });
            } else {
                $dbSubQuery->where(function ($query) use ($columnForQuery, $tableName, $caseLoweredQuery) {
                    $query->whereRaw("lower({$tableName}.{$columnForQuery}) LIKE ?", [$caseLoweredQuery]);
                });
            }
        }

        foreach ($filters as $column => $filter) {

            if (is_array($filter)) {

                if (DB::connection()->getDoctrineColumn($tableName, $column)->getType()->getName() == 'json') {
                    $dbSubQuery->whereJsonContains($column, $filter);
                } else {
                    $dbSubQuery->whereIn($column, $filter);
                }

            } else {

                if (str_starts_with($column, 'min')) {
                    $column = substr($column, 4);
                    $dbSubQuery->where($column, '>=', $filter);
                } else if (str_starts_with($column, 'max')) {
                    $column = substr($column, 4);
                    $dbSubQuery->where($column, '<=', $filter);
                } else if (str_starts_with($column, 'without')) {
                    $column = substr($column, 8);
                    $dbSubQuery->where($column, 0);
                } else if (str_starts_with($column, 'is')) {
                    $dbSubQuery->where($column, $filter);
                } else if (str_starts_with($column, 'has')) {
                    $column = substr($column, 4);
                    $dbSubQuery->where($column, '>', 0);
                } else {
                    $dbSubQuery->where(DB::raw("lower({$column})"), Str::lower($filter));
                }

            }
        }

        $dbQuery = $this->model::select()
            ->fromSub($dbSubQuery->getQuery(), $tableName);

        if (isset($options['sort_by'])) {
            if (isset($options['sort_dir'])) {
                $dbQuery->orderBy($options['sort_by'], $options['sort_dir']);
            } else {
                $dbQuery->orderBy($options['sort_by']);
            }
        }

        if (isset($options['date_range'])) {
            if ($options['date_range'] == 'month') {
                $dbQuery->whereBetween('updated_at', [now()->subMonth(), now()]);
            } elseif ($options['date_range'] == 'week') {
                $dbQuery->whereBetween('updated_at', [now()->subWeek(), now()]);
            } elseif ($options['date_range'] == 'day') {
                $dbQuery->whereBetween('updated_at', [now()->subDay(), now()]);
            }
        }

        if (isset($options['paginated']) && $options['paginated'] == 0) {
            $objects = $dbQuery->take(100)->get();
            $meta = ['meta' => ['total' => $objects->count()]];

            if ($objects->isEmpty()) {
                $response = array_merge([$tableName => []], $meta);

                return $response;
            }
        } else {

            if (isset($options['paginated'])) {
                $objects = $dbQuery->paginate($options['paginated']);
            } else {
                $objects = $dbQuery->paginate(20);
            }

            if (!$objects->hasPages()) {
                $meta = ['meta' => ['total' => $objects->total()]];
                $objects = $objects->items();

                if (!$objects) {
                    $response = array_merge([$tableName => []], $meta);

                    return $response;
                }
            }
        }

        return [
            'objects' => $objects,
            'meta' => $meta ?? null
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     * @return Model $model
     */
    public function create(array $attributes): Model
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
     * @return Model $model
     */
    public function find(int $id): Model
    {
        return $this->model->where('id', $id)->first();
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
        $model = $this->find($id);
        $model->update($attributes);
        $model->refresh();

        return $model;
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        return $this->find($id)->delete();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param array $ids
     * @return bool
     */
    public function massDestroy(array $ids): bool
    {
        return $this->model->destroy($ids);
    }

    /**
     * Prepare validated data with nullable fields.
     *
     * @param array $validated
     * @param array $nullableFields
     * @return array
     */
    protected function prepareUpdateValidated(array $validated, array $nullableFields): array
    {
        foreach ($nullableFields as $nullableField) {
            if (!isset($validated[$nullableField])) {
                $validated[$nullableField] = null;
            }
        }

        return $validated;
    }
}
