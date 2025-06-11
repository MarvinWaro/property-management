<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Office of the Regional Director',
            'Administrative Division - CAO',
            'Administrative Division - Accounting',
            'Administrative Division - Records',
            'Administrative Division - HEMIS',
            'Administrative Division - Cashier',
            'Scholarship Division - UniFAST Region XII',
            'Scholarship Division - UniFAST BARMM',
            'Scholarship Division - StuFAPs',
            'LGSO',
            'HECBOL',
            'Technical Division',
        ];

        foreach ($departments as $department) {
            Department::create([
                'name' => $department,
            ]);
        }
    }
}
