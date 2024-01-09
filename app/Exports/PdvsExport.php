<?php

namespace App\Exports;

use App\Models\Pdv;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PdvsExport implements FromCollection, WithMapping, WithHeadings
{
    protected $texto;

    public function __construct($texto = null)
    {
        $this->texto = $texto;
    }

    public function collection()
    {
        $query = Pdv::with('horaries');
        $status = null;

        if (strtolower($this->texto) === 'activo') {
            $status = 1;
        } elseif (strtolower($this->texto) === 'inactivo') {
            $status = 0;
        }

        if ($this->texto) {
            $query->where('spot', 'like', '%' . $this->texto . '%')
            ->orwhere('unit', 'like', '%' . $this->texto . '%')
            ->orWhere('status', $status)
            ->orderBy("id","asc");
        }

        return $query->get();
    }

    public function map($pdv): array
    {
        $horaries = $pdv->horaries->map(function ($horary) {
            return $horary->week . ': ' . $horary->hi . '-' . $horary->hs;
        })->implode(', ');

        return [
            $pdv->id,
            $pdv->unit,
            $pdv->spot,
            $pdv->status ? 'Activo' : 'Inactivo',
            $horaries,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Unidad',
            'Punto de venta',
            'Estado',
            'Horarios',
        ];
    }
}
