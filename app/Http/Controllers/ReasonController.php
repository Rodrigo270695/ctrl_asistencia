<?php

namespace App\Http\Controllers;

use App\Exports\ReasonsExport;
use App\Http\Requests\ReasonRequest;
use App\Models\Reason;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class ReasonController extends Controller
{

    public function index(): Response
    {
        $reasons = Reason::orderBy("id", "desc")->paginate(7);

        return Inertia::render('Absence/Reason/IndexReason', compact('reasons'));
    }

    public function store(ReasonRequest $request)
    {
        try {
            Reason::create($request->all());

            return redirect()->route('reason.index')->with('toast', ['Motivo creado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El motivo ya existe!', 'warning']);
            } else {
                return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
            }
        }
    }

    public function update(Request $request, Reason $reason)
    {
        try {
            $reason->update($request->all());

            return redirect()->route('reason.index')->with('toast', ['Motivo actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El motivo ya existe!', 'warning']);
            } else {
                return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
            }
        }
    }

    public function destroy(Reason $reason)
    {
        //
    }

    public function change(Reason $reason): RedirectResponse
    {
        if ($reason->status == 1) {
            $reason->status = 0;
        } else {
            $reason->status = 1;
        }

        $reason->save();
        return redirect()->route('reason.index')->with('toast', ['cambio de estado exitosamente!', 'success']);
    }

    public function search(Request $request)
    {
        $texto = $request->get('texto');
        $status = null;

        if (strtolower($texto) === 'activo') {
            $status = 1;
        } elseif (strtolower($texto) === 'inactivo') {
            $status = 0;
        }

        $reasons = Reason::where('name', 'LIKE', "%$texto%")
            ->orWhere('status', $status)
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Absence/Reason/IndexReason', compact('reasons', 'texto'));
    }

    public function export($texto = null)
    {
        $filename = 'Motivos_' . now()->format('d-m-Y_H-i') . '.xlsx';
        return Excel::download(new ReasonsExport($texto), $filename);
    }
}
