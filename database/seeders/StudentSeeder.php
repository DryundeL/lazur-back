<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('students')->insert([
            'first_name' => 'Student',
            'last_name' => 'Student',
            'patronymic_name' => 'Student',
            'email' => 'student@student.com',
            'password' => Hash::make('cqQPkYaoY40Q2ia'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
