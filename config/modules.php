<?php

return [

    'mattermost_url' => env('APP_MATTERMOST_URL', 'http://localhost'),

    'mattermost_admin_token' => env('APP_MATTERMOST_ADMIN_TOKEN', ''),

    'mattermost_team_id' => env('APP_MATTERMOST_TEAM_ID', ''),

    'path' => base_path() . 'app/Modules',
    'base_namespace' => 'App\Modules',

    'modules' => [
        'Admin' => [
            'Auth',
            'Audience',
            'ClassTime',
            'Discipline',
            'Employee',
            'Group',
            'Semester',
            'Speciality',
            'Student',
        ],
    ]
];
