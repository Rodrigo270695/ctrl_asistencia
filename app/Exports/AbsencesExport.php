<?php

namespace App\Exports;

use App\Models\Absence;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsencesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $texto;

    public function __construct($texto = null)
    {
        $this->texto = $texto;
    }

    public function collection()
    {
        $query = Absence::query();
        $user = Auth::user();

        $status = null;

        if (strtolower($this->texto) === 'activo') {
            $status = 1;
        } elseif (strtolower($this->texto) === 'inactivo') {
            $status = 0;
        }

        if ($user->roles->contains('name', 'supervisor')) {
            $query->whereHas('worker', function ($query) use ($user) {
                $query->where('pdv_id', $user->pdv->id);
            });
        }

        if ($this->texto) {
            $query->whereHas('worker', function ($query) {
                $query->where('name', 'like', '%' . $this->texto . '%')
                ->orWhere('lastname', 'like', '%' . $this->texto . '%')
                ->orWhere('num_document', 'like', '%' . $this->texto . '%');
            })
            ->orWhereHas('reason', function ($query) {
                $query->where('name', 'like', '%' . $this->texto . '%');
            })
            ->orWhere('status', 'like', '%' . $this->texto . '%')
            ->orderBy("id","asc");
        }

        return $query->with(['worker.pdv', 'reason'])->get();
    }

    public function map($absence): array
    {
        return [
            'id' => $absence->id,
            'start_date' => $absence->start_date,
            'end_date' => $absence->end_date,
            'reason_name' => $absence->reason->name,
            'worker_name' => $absence->worker->name,
            'worker_lastname' => $absence->worker->lastname,
            'worker_num_document' => $absence->worker->num_document,
            'pdv_name' => $absence->worker->pdv->spot,
            'status' => $absence->status,
            'status_detail' => $absence->status_detail,
            'created_at' => $absence->created_at->setTimezone('America/Lima')->format('d-m-Y H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha de Inicio',
            'Fecha de Fin',
            'Nombre de la Razón',
            'Nombre del Trabajador',
            'Apellido del Trabajador',
            'Número de Documento del Trabajador',
            'PDV',
            'Estado',
            'Detalle del Estado',
            'Fecha de Creación',
        ];
    }
}
