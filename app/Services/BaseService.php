<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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

    /**
     * Add personal access token user.
     *
     * @param $user
     * @return void
     */
    public function addToMatterMost($user)
    {
        $adminToken = config('modules.mattermost_admin_token');

        $teamId = config('modules.mattermost_team_id');

        $password = 'user' . $user->id . 'mattermost';

        $userData = [
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'password' => $password,
            'username' => Str::transliterate($user->last_name . '_' . $user->first_name . '_' . $user->id),
        ];

        $userId = Http::withHeaders([
            'Authorization' => 'Bearer ' . $adminToken,
        ])->post(config('modules.mattermost_url') . '/api/v4/users', $userData)
            ->json()['id'];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $adminToken,
        ])->post(config('modules.mattermost_url') . '/api/v4/users/' . $userId . '/tokens', [
            'description' => 'My access token',
        ]);

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $adminToken,
        ])->post(config('modules.mattermost_url') . '/api/v4/teams/' . $teamId . '/members', [
            'user_id' => $userId,
            'team_id' => $teamId,
        ]);

        $user->extended_token = $response->json()['token'] ?? null;
        $user->extended_user_id = $response->json()['user_id'] ?? null;

        $user->save();
    }
}
