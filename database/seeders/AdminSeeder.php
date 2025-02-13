<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Generate departments
        $departments = ['Administração', 'Recursos Humanos'];

        foreach($departments as $dep) {
            Department::create([
                'name' => $dep,
            ]);
        }

        // admin
        $user = User::create([
            'name' => 'Administrador',
            'email' => 'admin@rhmangnt.com',
            'email_verified_at' => now(),
            'password' => bcrypt('Aa123456'),
            'role' => 'admin',
            'permissions' => '["admin"]',
            'department_id' => 1,   // Administração
        ]);

        // admin details
        $user->detail()->create([
            'address' => 'Rua do Administrador, 123',
            'zip_code' => '12345-123',
            'city' => 'Rio de Janeiro',
            'phone' => '900000001',
            'salary' => 8000.00,
            'admission_date' => '2020-01-01',
        ]);
    }
}
