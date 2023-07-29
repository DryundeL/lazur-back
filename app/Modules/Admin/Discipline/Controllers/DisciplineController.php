<?php

namespace App\Modules\Admin\Discipline\Controllers;

use App\Http\Controllers\BaseController as Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Models\Discipline;
use App\Models\Employee;
use App\Models\Speciality;
use App\Modules\Admin\Discipline\Requests\SortDisciplineRequest;
use App\Modules\Admin\Discipline\Requests\StoreDisciplineRequest;
use App\Modules\Admin\Discipline\Resources\DisciplineCollection;
use App\Modules\Admin\Discipline\Resources\DisciplineResource;
use App\Modules\Admin\Discipline\Services\DisciplineService;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SortDisciplineRequest $request
     * @param DisciplineService $service
     * @return DisciplineCollection|JsonResponse
     */
    public function index(SortDisciplineRequest $request, DisciplineService $service): DisciplineCollection|JsonResponse
    {
        $subQueryArray = [];
        $filters = $request->validated();

        if (isset($filters['employee_id'])) {

            $subQueryArray = [
                'filterModel' => new Employee,
                'external_table' => 'discipline_employee',
                'condition_id' => $filters['employee_id']
            ];
            unset($filters['employee_id']);
        } else if (isset($filters['speciality_id'])) {

            $subQueryArray = [
                'filterModel' => new Speciality(),
                'external_table' => 'discipline_speciality',
                'condition_id' => $filters['speciality_id']
            ];
            unset($filters['speciality_id']);
        }

        $responseArray = $service->search($filters, [], $subQueryArray);

        if (!isset($responseArray['objects'])) {
            return $this->sendResponse($responseArray);
        } else {
            $response = new DisciplineCollection($responseArray['objects']);
            $meta = $responseArray['meta'];

            return (isset($meta))
                ? $response->additional($meta)
                : $response;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDisciplineRequest $request
     * @param DisciplineService $service
     * @return DisciplineResource
     */
    public function store(StoreDisciplineRequest $request, DisciplineService $service): DisciplineResource
    {
        $discipline = $service->create($request->validated());

        return new DisciplineResource($discipline);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return DisciplineResource
     */
    public function show(int $id): DisciplineResource
    {
        $discipline = Cache::remember(Discipline::getCacheKey($id), Carbon::now()->addMinutes(10), function () use ($id) {
            return Discipline::findOrFail($id);
        });

        return new DisciplineResource($discipline);
    }

    /**
     * Update a newly created resource in storage.
     *
     * @param StoreDisciplineRequest $request
     * @param DisciplineService $service
     * @param Discipline $discipline
     * @return DisciplineResource
     */
    public function update(StoreDisciplineRequest $request, DisciplineService $service, Discipline $discipline): DisciplineResource
    {
        $discipline = $service->update($request->validated(), $discipline->id);

        return new DisciplineResource($discipline);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param DisciplineService $service
     * @param Discipline $discipline
     * @return JsonResponse
     */
    public function destroy(DisciplineService $service, Discipline $discipline): JsonResponse
    {
        $service->destroy($discipline->id);

        return $this->sendResponse();
    }
}
