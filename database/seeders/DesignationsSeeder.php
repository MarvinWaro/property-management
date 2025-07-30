<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Designation;

class DesignationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            'Accounting Staff - Book Keeper',
            'Accounting Staff - Job Order',
            'Administrative Aide III',
            'Administrative Aide IV',
            'Administrative Aide VI',
            'Administrative Assistant II',
            'Administrative Officer III',
            'Administrative officer IV',
            'CAO - Project Support Staff',
            'Cashier Staff -Job Order',
            'Chief Administrative Officer',
            'Director III/Officer-in-Charge (OIC), Office of the Director IV',
            'Education Supervisor II',
            'Job Order - COA Staff',
            'Job Order - Technical Staff',
            'OIC-Chief Education Specialist',
            'Project Support Staff',
            'Project Support Staff V',
            'Project Technical Staff I',
            'Project Technical Staff II',
            'Project Technical Staff III',
            'Record\'s Staff - Job Order',
            'Security Guard',
            'Utility',
        ];

        foreach ($designations as $designation) {
            Designation::create([
                'name' => $designation,
                'description' => null, // Keep description blank as requested
            ]);
        }
    }

}
