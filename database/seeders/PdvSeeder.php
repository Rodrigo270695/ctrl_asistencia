<?php

namespace Database\Seeders;

use App\Models\Pdv;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PdvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["unit" => "DAM", "spot" => "CHICLAYO - MASIVO"],
        ];

        foreach ($data as $record) {
            Pdv::insert($record);
        }
    }
}
