<?php

namespace App\Http\Controllers;

use App\Models\Assist;
use App\Models\Worker;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssistController extends Controller
{

    public function index()
    {
        return Inertia::render('Assist/Index');
    }

    public function store(Request $request)
    {
        try {

            $assist = new Assist();
            $dayEn = now()->format('l');
            $dayEs = '';
            $status = '';
            $horaActual =  now()->format('H:i:s');
            $fechaActual = now()->format('Y-m-d');

            switch ($dayEn) {
                case 'Monday':
                    $dayEs = 'LUNES';
                    break;
                case 'Tuesday':
                    $dayEs = 'MARTES';
                    break;
                case 'Wednesday':
                    $dayEs = 'MIERCOLES';
                    break;
                case 'Thursday':
                    $dayEs = 'JUEVES';
                    break;
                case 'Friday':
                    $dayEs = 'VIERNES';
                    break;
                case 'Saturday':
                    $dayEs = 'SABADO';
                    break;
                case 'Sunday':
                    $dayEs = 'DOMINGO';
                    break;
                default:
                    $dayEs = 'DÍA NO RECONOCIDO';
                    break;
            }

            $worker = Worker::with(['pdv'])->find($request->id);
            $horarios = $worker->pdv->horaries;
            $existe = $horarios->firstWhere('week', $dayEs);

            if ($request->tipoRegistro === 'Ingreso') {

                $tolerancia = Carbon::parse($existe->hi)->addMinutes(5)->format('H:i:s');

                if ($horaActual > $tolerancia) {
                    $status = 'tarde';
                } elseif ($horaActual > $existe->hi && $horaActual < $tolerancia) {
                    $status = 'tolerancia';
                } else {
                    $status = 'ingreso';
                }
                $assist->hi = $horaActual;
                $assist->status_entry = $status;
            } elseif ($request->tipoRegistro === 'Refrigerio/S') {

                if ($horaActual > $existe->rs) {
                    $status = 'salida';
                } else {
                    $status = 'antes de tiempo';
                }
                $assist->rs = $horaActual;
                $assist->status_foodout = $status;
            } elseif ($request->tipoRegistro === 'Refrigerio/I') {

                $tolerancia = Carbon::parse($existe->ri)->addMinutes(5)->format('H:i:s');

                if ($horaActual > $tolerancia) {
                    $status = 'tarde';
                } elseif ($horaActual > $existe->ri && $horaActual < $tolerancia) {
                    $status = 'tolerancia';
                } else {
                    $status = 'ingreso';
                }
                $assist->ri = $horaActual;
                $assist->status_foodentry = $status;
            } elseif ($request->tipoRegistro === 'Salida') {

                if ($horaActual > $existe->hs) {
                    $status = 'salida';
                } else {
                    $status = 'antes de tiempo';
                }
                $assist->hs = $horaActual;
                $assist->status_out = $status;
            } else {
                return redirect()->route('assist.index')->with('toast', ['Necesita el tipo de registro', 'warning']);
            }

            $assist->worker_id = $request->id;
            $assist->current_date = $fechaActual;

            $existingAssist = Assist::where('worker_id', $request->id)->where('current_date', $fechaActual)->first();
            if ($existingAssist) {
                $existingAssist->update($assist->toArray());
            } else {
                $assist->save();
            }

            return redirect()->route('assist.index')->with('toast', ['Registro exitosamente!', 'success']);
        } catch (Exception $e) {
            return redirect()->route('assist.index')->with('toast', ['Ocurrió un error al registrar la asistencia.', 'danger']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Assist $assist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assist $assist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assist $assist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assist $assist)
    {
        //
    }

    public function getWorker($dni)
    {
        $worker = Worker::with(['pdv'])->where('num_document', $dni)->first();

        if (empty($dni) || is_null($worker)) {
            return Inertia::render('Assist/Index')->with('toast', ['Trabajador no encontrado', 'warning']);
        }

        return Inertia::render('Assist/Index', compact('worker', 'dni'))->with('toast', ['Trabajador encontrado', 'success']);
    }
}
