<?php

namespace Database\Seeders;

use App\Models\Worker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Worker::Create([
            'name' => 'Rodrigo',
            'lastname'=> 'Granja Requejo',
            'document_type' => 'DNI',
            'num_document'=> '77344506',
            'pdv_id'=> 112,
        ]);
    }
}
