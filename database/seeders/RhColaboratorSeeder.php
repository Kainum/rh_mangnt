<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;

class RhColaboratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create([
            'role' => 'rh',
            'permissions' => '["rh"]',
            'department_id' => 2,   // Recursos Humanos
        ])->each(function ($user) {
            $user->detail()->save(UserDetail::factory()->make());
        });
    }
}
