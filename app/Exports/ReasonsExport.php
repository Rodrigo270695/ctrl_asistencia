<?php

namespace App\Exports;

use App\Models\Reason;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReasonsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $texto;

    public function __construct($texto = null)
    {
        $this->texto = $texto;
    }

    public function collection()
    {
        $query = Reason::query();

        $status = null;

        if (strtolower($this->texto) === 'activo') {
            $status = 1;
        } elseif (strtolower($this->texto) === 'inactivo') {
            $status = 0;
        }

        if ($this->texto) {
            $query->where('name', 'like', '%' . $this->texto . '%')
            ->orWhere('description', 'like', '%' . $this->texto . '%')
            ->orWhere('status', $status)
            ->orderBy("id","asc");
        }

        return $query->get();
    }

    public function map($reason): array
    {
        return [
            'id' => $reason->id,
            'name' => $reason->name,
            'description' => $reason->description,
            'status' => $reason->status === 1 ? 'ACTIVO':'INACTIVO',
            'created_at' => $reason->created_at->setTimezone('America/Lima')->format('d-m-Y H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Descripcion',
            'Estado',
            'Fecha de Creación',
        ];
    }

}
