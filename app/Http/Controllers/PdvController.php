<?php

namespace App\Http\Controllers;

use App\Http\Requests\PdvRequest;
use App\Models\Horary;
use App\Models\Pdv;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Exports\PdvsExport;
use Maatwebsite\Excel\Facades\Excel;

class PdvController extends Controller
{

    public function index(): Response
    {
        $pdvs = Pdv::with('horaries')->orderBy("id","desc")->paginate(7);
        $horaries = Horary::where('status', 1)
        ->orderBy('week', 'asc')
        ->orderBy('hi', 'asc')
        ->get();

        return Inertia::render('Pdv/Index', compact('pdvs', 'horaries'));
    }

    public function store(PdvRequest $request): RedirectResponse
    {
        try {
            Pdv::create($request->all());
            return redirect()->route('pdv.index')->with('toast', ['Pdv creado exitosamente!','success']);
        } catch (QueryException $e) {

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El pdv ya existe!','warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function show(Pdv $pdv)
    {
        // Código para mostrar un Pdv
    }

    public function update(PdvRequest $request, Pdv $pdv): RedirectResponse
    {
        try {
            $pdv->update($request->all());
            return redirect()->route('pdv.index')->with('toast', ['Pdv actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El Pdv ya existe!', 'warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function change(Pdv $pdv): RedirectResponse
    {

        if ($pdv->status == 1) {
            $pdv->status = 0;
        }else {
            $pdv->status = 1;
        }

        $pdv->save();
        return redirect()->route('pdv.index')->with('toast', ['cambio de estado exitosamente!', 'success']);

    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $status = null;

        if (strtolower($texto) === 'activo') {
            $status = 1;
        } elseif (strtolower($texto) === 'inactivo') {
            $status = 0;
        }

        $pdvs = Pdv::with('horaries')
            ->where('unit', 'like', '%' . $texto . '%')
            ->orWhere('spot', 'like', '%' . $texto . '%')
            ->orWhere('status', $status)
            ->orderBy("id","desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        $horaries = Horary::where('status', 1)
            ->orderBy('week', 'asc')
            ->orderBy('hi', 'asc')
            ->get();

        return Inertia::render('Pdv/Index', compact('pdvs', 'horaries', 'texto'));
    }

    public function getHoraries(Pdv $pdv)
    {
        $horariesSe = $pdv->horaries;
        return response()->json(['horariesSe' => $horariesSe]);
    }

    public function updateHoraries(Request $request, Pdv $pdv)
    {
        $pdv->horaries()->detach();

        foreach ($request->input('horaries') as $week => $horaryId) {
            $pdv->horaries()->attach($horaryId);
        }

        return redirect()->route('pdv.index')->with('toast', ['Horarios Registrados!', 'success']);
    }

    public function export($texto = null)
    {
        $filename = 'pdvs_' . now()->format('d-m-Y_H-i') . '.xlsx';
        return Excel::download(new PdvsExport($texto), $filename);
    }

    public function getPdvsForPdf()
    {
        $pdvs = Pdv::all();
        return response()->json($pdvs);
    }

}
