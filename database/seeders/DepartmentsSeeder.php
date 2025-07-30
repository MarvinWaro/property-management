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
            'Administrative Division - Accounting',
            'Administrative Division - CAO',
            'Administrative Division - Cashier',
            'Administrative Division - HEMIS',
            'Administrative Division - Records',
            'Administrative Division - Supply and Procurement',
            'HECBOL',
            'LGSO',
            'MARITIME',
            'Office of the Regional Director',
            'Scholarship Division - StuFAPs',
            'Scholarship Division - UniFAST BARMM',
            'Scholarship Division - UniFAST Region XII',
            'SPORTS',
            'STUFAPS',
            'Technical Division',
            'TNHE',
        ];


        foreach ($departments as $department) {
            Department::create([
                'name' => $department,
            ]);
        }
    }
}
