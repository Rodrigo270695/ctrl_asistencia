<?php

namespace Database\Seeders;

use App\Models\Reason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reason::create([
            'name' => 'VACACIONES'
        ]);
        Reason::create([
            'name' => 'DESCANSO MEDICO'
        ]);
        Reason::create([
            'name' => 'PATERNIDAD'
        ]);
        Reason::create([
            'name' => 'LLUVIAS'
        ]);
    }
}
