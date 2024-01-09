<?php

namespace App\Exports;

use App\Models\Horary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HorariesExport implements FromCollection, WithMapping, WithHeadings
{
    protected $texto;

    public function __construct($texto = null)
    {
        $this->texto = $texto;
    }

    public function collection()
    {
        $query = Horary::query();
        $status = null;

        if (strtolower($this->texto) === 'activo') {
            $status = 1;
        } elseif (strtolower($this->texto) === 'inactivo') {
            $status = 0;
        }

        if ($this->texto) {
            $query->where('week', 'like', '%' . $this->texto . '%')
            ->orWhere('status', $status)
            ->orderBy("id","asc");
        }

        return $query->get();
    }

    public function map($horary): array
    {
        return [
            $horary->id,
            $horary->week,
            $horary->hi,
            $horary->rs,
            $horary->ri,
            $horary->hs,
            $horary->status ? 'Activo' : 'Inactivo',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Semana',
            'Hora de inicio',
            'Hora de descanso',
            'Hora de reinicio',
            'Hora de finalización',
            'Estado',
        ];
    }
}
