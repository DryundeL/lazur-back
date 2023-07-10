<?php

namespace App\Services;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
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
     * @param array|null $columnsForQuery
     * @param array|null $subQueryArray
     * @param bool $areAllObjects
     * @return array
     */
    public function search(array $options, array $columnsForQuery = null, array $subQueryArray = null, bool $areAllObjects = false): array
    {
        $queries = array_filter($options, function ($v, $k) {
            return str_starts_with($k, 'query');
        }, ARRAY_FILTER_USE_BOTH);

        $filters = array_diff_key($options, array_flip([
            'sort_by',
            'sort_dir',
            'date_range',
            'paginated',
        ]), $queries);

        if ($subQueryArray) {
            $model = $subQueryArray['searchModel'] ?? $this->model;

            $tableName = $model->getTable();
            $columns = $model->getFillable();

            $dbSubQuery = $model::select("{$tableName}.*");

            if (isset($subQueryArray['external_table'])) {

                if (isset($subQueryArray['searchModel'])) {
                    $secondTable = $this->model->getTable();
                } else {
                    $secondTable = $subQueryArray['filterModel']->getTable();
                }

                $singFindTable = Str::singular($tableName);
                $singSecondTable = Str::singular($secondTable);
                $externalTableName = $subQueryArray['external_table'];

                $dbSubQuery->leftJoin($externalTableName, "{$externalTableName}.{$singFindTable}_id",
                    '=', "{$tableName}.id")
                            ->leftJoin($secondTable, "{$externalTableName}.{$singSecondTable}_id",
                    '=', "{$secondTable}.id")
                            ->where("{$secondTable}.id", $subQueryArray['condition_id']);
            } else {
                $singFirstTable = Str::singular($tableName);
                $additionalTable = $subQueryArray['filterModel']->getTable();

                $dbSubQuery->leftJoin($additionalTable, "{$additionalTable}.{$singFirstTable}_id",
                    '=',  "{$tableName}.id")
                            ->where("{$additionalTable}.id", $subQueryArray['condition_id']);
            }

        } else {
            $dbSubQuery = $this->model::select();

            $columns = $this->model->getFillable();
            $tableName = $this->model->getTable();
        }

        if (!$areAllObjects) {
            if (isset($columns['is_active'])) {
                $dbSubQuery->where('is_active', true);
            }

            if (isset($columns['is_approved'])) {
                $dbSubQuery->where('is_approved', true);
            }
        }

        if ($queries) {

            foreach ($queries as $queryKey => $query) {
                $caseLoweredQuery = '%' . Str::lower($query) . '%';
                $column = substr($queryKey, 6);

                if (isset($columnsForQuery[$column])) {
                    $column = $columnsForQuery[$column];
                }

                $dbSubQuery->where(function ($query) use ($column, $tableName, $caseLoweredQuery) {
                    $query->whereRaw("lower(concat({$column})) LIKE ?", [$caseLoweredQuery]);
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
                    $dbSubQuery->where("{$tableName}.{$column}", '>=', $filter);
                } else if (str_starts_with($column, 'max')) {
                    $column = substr($column, 4);
                    $dbSubQuery->where("{$tableName}.{$column}", '<=', $filter);
                } else if (str_starts_with($column, 'without')) {
                    $column = substr($column, 8);
                    $dbSubQuery->where("{$tableName}.{$column}", 0);
                } else if (str_starts_with($column, 'is')) {
                    $dbSubQuery->where("{$tableName}.{$column}", $filter);
                } else if (str_starts_with($column, 'has')) {
                    $column = substr($column, 4);
                    $dbSubQuery->where("{$tableName}.{$column}", '>', 0);
                } else {
                    $dbSubQuery->where(DB::raw("{$tableName}.{$column}"), Str::lower($filter));
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
            $gap = match ($options['date_range']) {
                'month' => now()->subMonth(),
                'week' => now()->subWeek(),
                'day' => now()->subDay(),
            };
            $dbQuery->whereBetween('updated_at', [$gap, now()]);
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
        Cache::put($model->getCacheKey($model->id), $model, Carbon::now()->addMinutes(15));

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
        Cache::put($model->getCacheKey($id), $model, Carbon::now()->addMinutes(15));

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
        Cache::forget($this->model->getCacheKey($id));
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
        foreach ($ids as $id) {
            Cache::forget($this->model->getCacheKey($id));
        }

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
