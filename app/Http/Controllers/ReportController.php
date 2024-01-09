<?php

namespace App\Http\Controllers;

use App\Exports\AssistsExport;
use App\Models\Assist;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{

    public function index()
    {
        $assists = Assist::with('worker')->orderBy('updated_at', 'desc')->paginate(10);

        return Inertia::render('Report/Index', compact('assists'));
    }

    public function search(Request $request)
    {
        $texto = $request->texto;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;

        try {
            $query = Assist::with('worker');

            if ($texto) {
                $query->where(function ($query) use ($texto) {
                    $query->whereHas('worker', function ($q) use ($texto) {
                        $q->where('name', 'like', "%{$texto}%")
                            ->orWhere('lastname', 'like', "%{$texto}%")
                            ->orWhere('num_document', 'like', "%{$texto}%");
                    })
                    ->orWhere('status_entry', 'like', "%{$texto}%")
                    ->orWhere('status_foodout', 'like', "%{$texto}%")
                    ->orWhere('status_foodentry', 'like', "%{$texto}%")
                    ->orWhere('status_out', 'like', "%{$texto}%");
                });
            }

            if ($fromDate && $toDate) {
                $query->whereBetween('current_date', [$fromDate, $toDate]);
            }

            $assists = $query->orderBy('updated_at', 'desc')
                ->paginate(10)
                ->appends(['texto' => $texto, 'fromDate' => $fromDate, 'toDate' => $toDate]);

            return Inertia::render('Report/Index', compact('assists', 'texto', 'fromDate', 'toDate'));
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Revice las fechas', 'warning']);
        }
    }

    public function export($texto = null)
    {
        $filename = 'Asistencias_' . now()->format('d-m-Y_H-i') . '.xlsx';
        return Excel::download(new AssistsExport($texto), $filename);
    }

}
