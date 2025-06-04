<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        Department::create([
            'name' => 'Human Resources',
            'code' => 'HR',
            'description' => 'Human Resources Department',
        ]);

        Department::create([
            'name' => 'Information Technology',
            'code' => 'IT',
            'description' => 'Information Technology Department',
        ]);

        Department::create([
            'name' => 'Finance',
            'code' => 'FIN',
            'description' => 'Finance Department',
        ]);

        Department::create([
            'name' => 'Marketing',
            'code' => 'MKT',
            'description' => 'Marketing Department',
        ]);
    }
}