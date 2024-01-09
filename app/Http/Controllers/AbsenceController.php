<?php

namespace App\Http\Controllers;

use App\Exports\AbsencesExport;
use App\Http\Requests\AbsenceRequest;
use App\Models\Absence;
use App\Models\Reason;
use App\Models\Worker;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class AbsenceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = now()->format('Y-m-d');

        if ($user->roles->contains('name', 'supervisor')) {
            $absences = Absence::with(['worker.pdv', 'reason'])
                ->join('workers', 'absences.worker_id', '=', 'workers.id')
                ->join('pdvs', 'workers.pdv_id', '=', 'pdvs.id')
                ->where('pdvs.spot', $user->pdv->spot)
                ->whereDate('absences.created_at', $today)
                ->latest('absences.updated_at')
                ->select('absences.*')
                ->paginate(7);
            $workers = Worker::where('status', 1)->where('pdv_id', $user->pdv->id)->get();
        } else {
            $absences = Absence::with(['worker.pdv', 'reason'])
                ->whereDate('created_at', $today)
                ->latest('updated_at')
                ->paginate(7);
            $workers = Worker::where('status', 1)->get();
        }

        $reasons = Reason::where('status', 1)->get();

        return Inertia::render('Absence/Abs/IndexAbs', compact('absences', 'workers', 'reasons'));
    }

    public function base(): Response
    {
        return Inertia::render('Absence/IndexGeneral');
    }

    public function show()
    {
    }

    public function store(AbsenceRequest $request)
    {
        try {
            $absence = new Absence;
            $absence->start_date = $request->start_date;
            $absence->end_date = $request->end_date;
            $absence->reason_id = $request->reason_id;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $absence->file = $filename;
            }
            $absence->worker_id = $request->worker_id;
            $absence->save();

            return redirect()->route('abs.index')->with('toast', ['Inasistencia registrado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(AbsenceRequest $request, String $id)
    {
        try {
            $absence = Absence::findOrFail($id);
            if ($absence->status == 'POR APROBAR' | $absence->status == 'OBSERVADO') {
                $absence->update([
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'reason_id' => $request->reason_id,
                    'worker_id' => $request->worker_id,
                    'status_detail' => $request->status_detail,
                    'status' => $request->status,
                ]);
                return redirect()->route('abs.index')->with('toast', ['Inasistencia actualizada exitosamente!', 'success']);
            } else {
                return redirect()->back()->with('toast', ['El estado ya ha sido actualizado por gerencia!', 'warning']);
            }
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        try {
            $absence = Absence::findOrFail($id);

            if ($absence->status ==  'POR APROBAR') {
                $absence->delete();
                return redirect()->route('abs.index')->with('toast', ['Inasistencia eliminada exitosamente!', 'success']);
            } else {
                return redirect()->back()->with('toast', ['La inasistencia ya ha sido aprobada y no puede ser eliminada!', 'warning']);
            }
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function updateFile(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|max:3072',
        ]);

        $absence = Absence::findOrFail($id);

        if ($request->hasFile('file')) {
            try {
                // Elimina el archivo antiguo si existe
                if ($absence->file) {
                    $oldFile = public_path('uploads') . '/' . $absence->file;
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                // Guarda el nuevo archivo
                $file = $request->file('file');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);

                $absence->file = $filename;
                $absence->save();

                return redirect()->route('abs.index')->with('toast', ['Archivo actualizado con éxito!', 'success']);
            } catch (QueryException $e) {
                return redirect()->back()->with('toast', ['Ocurrió un error al actualizar el archivo!, comuníquese con gerencia', 'warning']);
            }
        }
    }

    public function search(Request $request)
    {
        $texto = $request->get('texto');
        $user = auth()->user();

        $query = Absence::with(['worker.pdv', 'reason'])
            ->join('workers', 'absences.worker_id', '=', 'workers.id')
            ->join('pdvs', 'workers.pdv_id', '=', 'pdvs.id')
            ->join('reasons', 'absences.reason_id', '=', 'reasons.id');

        if ($user->roles->contains('name', 'supervisor')) {
            $query->where('pdvs.spot', $user->pdv->spot);
            $workers = Worker::where('status', 1)->where('pdv_id', $user->pdv->id)->get();
        } else {
            $workers = Worker::where('status', 1)->get();
        }

        if ($texto === 'all') {
            // No se aplica ningún filtro de fecha
        } elseif (strtotime($texto)) {
            $query->whereDate('absences.created_at', $texto);
        } else {
            $query->where(function ($query) use ($texto) {
                $query->where('workers.name', 'like', '%' . $texto . '%')
                    ->orWhere('workers.lastname', 'like', '%' . $texto . '%')
                    ->orWhere('workers.num_document', 'like', '%' . $texto . '%')
                    ->orWhere('reasons.name', 'like', '%' . $texto . '%')
                    ->orWhere('absences.status', 'like', '%' . $texto . '%'); // Buscar por el estado de la ausencia
            });
        }

        $absences = $query->select('absences.*')->orderBy('absences.created_at', 'desc')->paginate(7)->appends(['texto' => $texto]);;

        $reasons = Reason::where('status', 1)->get();

        return Inertia::render('Absence/Abs/IndexAbs', compact('absences', 'workers', 'reasons', 'texto'));
    }

    public function export($texto = null)
    {
        $filename = 'Inasistencias_' . now()->format('d-m-Y_H-i') . '.xlsx';
        return Excel::download(new AbsencesExport($texto), $filename);
    }

}
