<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Médecin
        User::create([
            'name' => 'Dr. Ahmed Medecin',
            'email' => 'medecin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'medecin',
        ]);

        // Patient
        User::create([
            'name' => 'User Patient',
            'email' => 'patient@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'patient',
        ]);
    }
}
