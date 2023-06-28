<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Success response method.
     *
     * @param array $result
     * @param int $code
     * @return JsonResponse
     */
    protected function sendResponse(array $result = [], int $code = 200)
    {
        return response()->json($result, $code);
    }

    /**
     * Error response method.
     *
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    protected function sendErrorResponse(array $errorMessages = [], int $code = 404)
    {
        return response()->json($errorMessages, $code);
    }

    /**
     * Prepare validated data with nullable fields.
     *
     * @param array $validated
     * @param array $nullableFields
     * @return array
     */
    protected function prepareUpdateValidated(array $validated, array $nullableFields)
    {
        foreach ($nullableFields as $nullableField) {
            if (!isset($validated[$nullableField])) {
                $validated[$nullableField] = null;
            }
        }

        return $validated;
    }
}
