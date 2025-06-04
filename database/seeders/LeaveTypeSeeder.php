<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        LeaveType::create([
            'name' => 'Annual Leave',
            'code' => 'AL',
            'max_days_per_year' => 21,
        ]);

        LeaveType::create([
            'name' => 'Sick Leave',
            'code' => 'SL',
            'max_days_per_year' => 10,
        ]);

        LeaveType::create([
            'name' => 'Personal Leave',
            'code' => 'PL',
            'max_days_per_year' => 5,
        ]);

        LeaveType::create([
            'name' => 'Emergency Leave',
            'code' => 'EL',
            'max_days_per_year' => 3,
        ]);
    }
}