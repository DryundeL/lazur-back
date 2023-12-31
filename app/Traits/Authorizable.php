<?php

namespace App\Traits;

use App\Models\Employee;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait Authorizable
{
    /**
     * Logout user.
     */
    public function logout(): void
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
    }

    /**
     * Add personal access token user.
     *
     * @param $user
     * @return boolean
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
            'username' => $user instanceof Employee
                ? Str::transliterate('e_' . $user->last_name . '_' . $user->first_name . '_' . $user->id)
                : Str::transliterate('s_' . $user->last_name . '_' . $user->first_name . '_' . $user->id),
        ];

        try {
            $userId = Http::withHeaders([
                'Authorization' => 'Bearer ' . $adminToken,
            ])->post(config('modules.mattermost_url') . '/api/v4/users', $userData)
                ->json()['id'];
        } catch (Exception $e) {
            return false;
        }

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

        return true;
    }
}
