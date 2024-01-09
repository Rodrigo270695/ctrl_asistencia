<?php

namespace Database\Seeders;

use App\Models\Horary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HorarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Horary::create([
            'week' => 'LUNES',
            'hi'=> '08:30',
            'rs'=> '13:30',
            'ri'=> '15:00',
            'hs'=> '18:30',
        ]);
        Horary::create([
            'week' => 'MARTES',
            'hi'=> '08:30',
            'rs'=> '13:30',
            'ri'=> '15:00',
            'hs'=> '18:30',
        ]);
        Horary::create([
            'week' => 'MIERCOLES',
            'hi'=> '08:30',
            'rs'=> '13:30',
            'ri'=> '15:00',
            'hs'=> '18:30',
        ]);
        Horary::create([
            'week' => 'JUEVES',
            'hi'=> '08:30',
            'rs'=> '13:30',
            'ri'=> '15:00',
            'hs'=> '18:30',
        ]);
        Horary::create([
            'week' => 'VIERNES',
            'hi'=> '08:30',
            'rs'=> '13:30',
            'ri'=> '15:00',
            'hs'=> '18:30',
        ]);
        Horary::create([
            'week' => 'SABADO',
            'hi'=> '08:30',
            'rs'=> '00:00',
            'ri'=> '00:00',
            'hs'=> '13:30',
        ]);
        Horary::create([
            'week' => 'DOMINGO',
            'hi'=> '00:00',
            'rs'=> '00:00',
            'ri'=> '00:00',
            'hs'=> '00:00',
        ]);
    }
}
