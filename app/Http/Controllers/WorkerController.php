<?php

namespace App\Http\Controllers;

use App\Exports\WorkersExport;
use App\Http\Requests\WorkerRequest;
use App\Models\Pdv;
use App\Models\Worker;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class WorkerController extends Controller
{

    public function index(): Response
    {
        $workers = Worker::with(['pdv'])->orderBy("id","desc")->paginate(7);
        $pdvs = Pdv::where('status', 1)->get();

        return Inertia::render('Worker/Index', compact('workers','pdvs'));
    }

    public function store(WorkerRequest $request): RedirectResponse
    {
        try {
            Worker::create($request->all());
            return redirect()->route('worker.index')->with('toast', ['Trabajador creado exitosamente!','success']);
        } catch (QueryException $e) {

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El trabajador ya existe!','warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function show(Worker $worker)
    {

    }

    public function update(WorkerRequest $request, Worker $worker)
    {
        try {
            $worker->update($request->all());
            return redirect()->route('worker.index')->with('toast', ['Trabajador actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Trabajador ya existe!', 'warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function change(Worker $worker): RedirectResponse
    {

        if ($worker->status == 1) {
            $worker->status = 0;
        }else {
            $worker->status = 1;
        }

        $worker->save();
        return redirect()->route('worker.index')->with('toast', ['cambio de estado exitosamente!', 'success']);

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

        $workers = Worker::with(['pdv'])
            ->where('name', 'LIKE', "%$texto%")
            ->orWhere('lastname', 'LIKE', "%$texto%")
            ->orWhere('document_type', 'LIKE', "%$texto%")
            ->orWhere('num_document', 'LIKE', "%$texto%")
            ->orWhere('charge', 'LIKE', "%$texto%")
            ->orWhereHas('pdv', function ($query) use ($texto) {
                $query->where('spot', 'LIKE', "%$texto%");
            })
            ->orWhere('status', $status)
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        $pdvs = Pdv::where('status', 1)->get();

        return Inertia::render('Worker/Index', compact('workers', 'pdvs', 'texto'));
    }

    public function export($texto = null)
    {
        $filename = 'trabajadores_' . now()->format('d-m-Y_H-i') . '.xlsx';
        return Excel::download(new WorkersExport($texto), $filename);
    }

}
