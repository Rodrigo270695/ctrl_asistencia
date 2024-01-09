<?php

namespace App\Exports;

use App\Models\Worker;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WorkersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $texto;

    public function __construct($texto = null)
    {
        $this->texto = $texto;
    }

    public function collection()
    {
        $query = Worker::with('pdv');

        $status = null;

        if (strtolower($this->texto) === 'activo') {
            $status = 1;
        } elseif (strtolower($this->texto) === 'inactivo') {
            $status = 0;
        }

        if ($this->texto) {
            $query->where('name', 'like', '%' . $this->texto . '%')
            ->orWhere('lastname', 'like', '%' . $this->texto . '%')
            ->orWhere('document_type', 'like', '%' . $this->texto . '%')
            ->orWhere('num_document', 'like', '%' . $this->texto . '%')
            ->orWhere('charge', 'LIKE', '%' . $this->texto . '%')
            ->orWhereHas('pdv', function ($query) {
                $query->where('spot', 'like', '%' . $this->texto . '%');
            })
            ->orWhere('status', $status)
            ->orderBy("id","asc");
        }

        return $query->get();
    }

    public function map($worker): array
    {
        return [
            'id' => $worker->id,
            'name' => $worker->name,
            'lastname' => $worker->lastname,
            'document_type' => $worker->document_type,
            'num_document' => $worker->num_document,
            'entry_date' => $worker->entry_date,
            'charge' => $worker->charge,
            'phone' => $worker->phone,
            'email' => $worker->email,
            'address' => $worker->address,
            'status' => $worker->status === 1 ? 'ACTIVO':'INACTIVO',
            'pdv_name' => $worker->pdv->spot,
            'created_at' => $worker->created_at->setTimezone('America/Lima')->format('d-m-Y H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Apellido',
            'Tipo de Documento',
            'Número de Documento',
            'Fecha de Ingreso',
            'Cargo',
            'Teléfono',
            'Correo Electrónico',
            'Dirección',
            'Estado',
            'Nombre de PDV',
            'Fecha de Creación',
        ];
    }
}
