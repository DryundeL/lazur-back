<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->insert([
            'first_name' => 'Employee',
            'last_name' => 'Employee',
            'patronymic_name' => 'Employee',
            'email' => 'employee@employee.com',
            'password' => Hash::make('cqQPkYaoY40Q2ia'),
            'role' => 'teacher',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
