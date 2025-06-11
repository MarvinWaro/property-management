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

        Designation::create([
            'name' => 'Regional Director',
            'description' => null, // Keep description blank as requested
        ]);

        Designation::create([
            'name' => 'Chief Administrative Officer',
            'description' => null, // Keep description blank as requested
        ]);

        Designation::create([
            'name' => 'Project Technical Staff III',
            'description' => null, // Keep description blank as requested
        ]);

        Designation::create([
            'name' => 'Project Technical Staff II',
            'description' => null, // Keep description blank as requested
        ]);

        Designation::create([
            'name' => 'Project Technical Staff I',
            'description' => null, // Keep description blank as requested
        ]);

        Designation::create([
            'name' => 'Administrative Aide VI',
            'description' => null, // Keep description blank as requested
        ]);

        Designation::create([
            'name' => 'Administrative Aide V',
            'description' => null, // Keep description blank as requested
        ]);

        Designation::create([
            'name' => 'Administrative Aide IV',
            'description' => null, // Keep description blank as requested
        ]);

        Designation::create([
            'name' => 'Administrative Aide III',
            'description' => null, // Keep description blank as requested
        ]);

        Designation::create([
            'name' => 'Job-Order',
            'description' => null, // Keep description blank as requested
        ]);

        Designation::create([
            'name' => 'OJT',
            'description' => null, // Keep description blank as requested
        ]);
    }
}
