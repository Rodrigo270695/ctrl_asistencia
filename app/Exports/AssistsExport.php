<?php

namespace App\Exports;

use App\Models\Assist;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssistsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $texto;

    public function __construct($texto = null)
    {
        $this->texto = $texto;
    }

    public function collection()
    {
        $query = Assist::with('worker');

        if ($this->texto) {
            $query->whereHas('worker', function ($query) {
                $query->where('name', 'like', '%' . $this->texto . '%')
                ->orWhere('lastname', 'like', '%' . $this->texto . '%')
                ->orWhere('num_document', 'like', '%' . $this->texto . '%');
            })
            ->orWhere('status_entry', 'like', '%' . $this->texto . '%')
            ->orWhere('status_foodout', 'like', '%' . $this->texto . '%')
            ->orWhere('status_foodentry', 'like', '%' . $this->texto . '%')
            ->orWhere('status_out', 'like', '%' . $this->texto . '%')
            ->orderBy("id","asc");
        }

        return $query->get();
    }

    public function map($assist): array
    {
        return [
            'id' => $assist->id,
            'current_date' => $assist->current_date,
            'worker_name' => $assist->worker->name,
            'worker_lastname' => $assist->worker->lastname,
            'worker_num_document' => $assist->worker->num_document,
            'hi' => $assist->hi,
            'status_entry' => $assist->status_entry,
            'rs' => $assist->rs,
            'status_foodout' => $assist->status_foodout,
            'ri' => $assist->ri,
            'status_foodentry' => $assist->status_foodentry,
            'hs' => $assist->hs,
            'status_out' => $assist->status_out,

            'created_at' => $assist->created_at->setTimezone('America/Lima')->format('d-m-Y H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha',
            'Nombre del Trabajador',
            'Apellido del Trabajador',
            'Número de Documento del Trabajador',
            'HI',
            'Estado de Entrada',
            'RS',
            'Estado de Salida de Comida',
            'RI',
            'Estado de Entrada de Comida',
            'HS',
            'Estado de Salida',
            'Fecha de Creación',
        ];
    }
}
