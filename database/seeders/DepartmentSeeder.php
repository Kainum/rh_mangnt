<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Financeiro',
            'Comercial',
            'Marketing',
            'Tecnologia da Informação',
            'Produção',
            'Logística',
            'Qualidade',
            'Manutenção',
        ];

        foreach($departments as $dep) {
            Department::create([
                'name' => $dep,
            ]);
        }
    }
}
