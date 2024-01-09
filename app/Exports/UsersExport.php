<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithMapping, WithHeadings
{
    protected $texto;

    public function __construct($texto = null)
    {
        $this->texto = $texto;
    }

    public function collection()
    {
        $query = User::with('pdv');

        if ($this->texto) {
            $query->where('name', 'like', '%' . $this->texto . '%')
            ->orWhere('email', 'like', '%' . $this->texto . '%')
            ->orWhereHas('roles', function ($q) {
                $q->where('name', 'like', '%' . $this->texto . '%');
            })
            ->orWhereHas('pdv', function ($q) {
                $q->where('spot', 'like', '%' . $this->texto . '%');
            })
            ->orderBy("id","asc");
        }

        return $query->get();
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->roles->pluck('name')->join(', '),
            $user->pdv->spot,
            $user->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Correo electrónico',
            'Roles',
            'PDV',
            'Fecha de creación',
        ];
    }
}
