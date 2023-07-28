<?php

namespace App\Modules\Admin\Employee\Services;

use App\Services\BaseService;
use App\Traits\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Models\Employee;

class EmployeeService extends BaseService
{
    use Authorizable;

    public function __construct(Employee $employee)
    {
        $this->model = $employee;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     * @return array $model
     */
    public function createEmployee(array $attributes): array
    {
        $employee = $this->model;

        $employee->fill($attributes);
        $employee->save();
        $employee->disciplines()->attach($attributes['disciplines']);

        return ['employee' => $employee, 'mm_status' => $this->addToMatterMost($employee)];
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
        $employee = $this->find($id);
        $employee->update($attributes);
        $employee->refresh();
        $employee->disciplines()->sync($attributes['disciplines']);

        Cache::put($employee->getCacheKey($id), $employee, Carbon::now()->addMinutes(15));

        return $employee;
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
        $employee = $this->find($id);
        $employee->disciplines()->detach();

        return $employee->delete();
    }
}
