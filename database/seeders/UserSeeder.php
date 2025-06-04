<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();

        // Create Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@company.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'employee_id' => 'EMP001',
            'department_id' => $departments->where('code', 'IT')->first()->id,
        ]);

        // Create Managers
        $hrManager = User::create([
            'name' => 'HR Manager',
            'email' => 'hr.manager@company.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'employee_id' => 'EMP002',
            'department_id' => $departments->where('code', 'HR')->first()->id,
        ]);

        $itManager = User::create([
            'name' => 'IT Manager',
            'email' => 'it.manager@company.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'employee_id' => 'EMP003',
            'department_id' => $departments->where('code', 'IT')->first()->id,
        ]);

        // Update departments with managers
        Department::where('code', 'HR')->update(['manager_id' => $hrManager->id]);
        Department::where('code', 'IT')->update(['manager_id' => $itManager->id]);

        // Create Employees
        User::create([
            'name' => 'John Employee',
            'email' => 'john@company.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'employee_id' => 'EMP004',
            'department_id' => $departments->where('code', 'IT')->first()->id,
        ]);

        User::create([
            'name' => 'Jane Employee',
            'email' => 'jane@company.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'employee_id' => 'EMP005',
            'department_id' => $departments->where('code', 'HR')->first()->id,
        ]);
    }
}