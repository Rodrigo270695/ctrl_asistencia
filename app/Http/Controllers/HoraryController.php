<?php

namespace App\Http\Controllers;

use App\Exports\HorariesExport;
use App\Http\Requests\HoraryRequest;
use App\Models\Horary;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class HoraryController extends Controller
{

    public function index(): Response
    {
        $horaries = Horary::orderBy("id", "desc")->paginate(7);

        return Inertia::render('Horary/Index', compact('horaries'));
    }

    public function store(HoraryRequest $request): RedirectResponse
    {
        try {
            Horary::create($request->all());
            return redirect()->route('horary.index')->with('toast', ['Horario creado exitosamente!', 'success']);
        } catch (QueryException $e) {

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El horario ya existe!', 'warning']);
            } else {
                return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
            }
        }
    }
    public function update(Request $request, Horary $horary): RedirectResponse
    {
        try {
            $horary->update($request->all());
            return redirect()->route('horary.index')->with('toast', ['Horario actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El horario ya existe!', 'warning']);
            } else {
                return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
            }
        }
    }


    public function change(Horary $horary): RedirectResponse
    {

        if ($horary->status == 1) {
            $horary->status = 0;
        } else {
            $horary->status = 1;
        }

        $horary->save();
        return redirect()->route('horary.index')->with('toast', ['cambio de estado exitosamente!', 'success']);
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $horaries = Horary::where('week', 'like', '%' . $texto . '%')
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);


        return Inertia::render('Horary/Index', compact('horaries','texto'));
    }

    public function export($texto = null)
    {
        $filename = 'horarios_' . now()->format('d-m-Y_H-i') . '.xlsx';
        return Excel::download(new HorariesExport($texto), $filename);
    }
}
