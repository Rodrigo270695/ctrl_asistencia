<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AssistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HoraryController;
use App\Http\Controllers\PdvController;
use App\Http\Controllers\ReasonController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/offline', function () {
    return view('modules/laravelpwa/offline');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'),'verified'])->group(function () {

    /* PDV */
    Route::get('pdv/search', [PdvController::class, 'search'])->name('pdv.search');
    Route::get('pdv/export/{texto?}', [PdvController::class, 'export'])->name('pdv.export');
    Route::get('pdv/getPdvsForPdf', [PdvController::class, 'getPdvsForPdf'])->name('pdv.getPdvsForPdf');
    Route::resource('pdv', PdvController::class);
    Route::put('pdv/change/{pdv}', [PdvController::class, 'change'])->name('pdv.change');
    Route::put('/pdv/{pdv}/updateHoraries', [PdvController::class, 'updateHoraries'])->name('pdv.updateHoraries');

    /* Worker */
    Route::get('worker/search', [WorkerController::class, 'search'])->name('worker.search');
    Route::get('worker/export/{texto?}', [WorkerController::class, 'export'])->name('worker.export');
    Route::resource('worker', WorkerController::class);
    Route::put('worker/change/{worker}', [WorkerController::class, 'change'])->name('worker.change');

    /* User */
    Route::get('user/search', [UserController::class, 'search'])->name('user.search');
    Route::get('user/export/{texto?}', [UserController::class, 'export'])->name('user.export');
    Route::resource('user', UserController::class);
    /* Assists */
    Route::resource('assist', AssistController::class);
    Route::get('/assist/worker/dni/{dni}', [AssistController::class, 'getWorker'])->name('assist.worker');

    /* Horary */
    Route::get('horary/search', [HoraryController::class, 'search'])->name('horary.search');
    Route::get('horary/export/{texto?}', [HoraryController::class, 'export'])->name('horary.export');
    Route::resource('horary', HoraryController::class);
    Route::put('horary/change/{horary}', [HoraryController::class, 'change'])->name('horary.change');

    Route::get('absence', [AbsenceController::class, 'base'])->name('abs.base');

    /* Inasistencias */
    Route::get('/absence/abs/search', [AbsenceController::class, 'search'])->name('abs.search');
    Route::get('absence/abs/export/{texto?}', [AbsenceController::class, 'export'])->name('abs.export');
    Route::get('/absence/abs/filterByDate/{dateFilter}', [AbsenceController::class, 'filterByDate'])->name('abs.filterByDate');
    Route::resource('absence/abs', AbsenceController::class);
    Route::put('/absence/abs/{id}/update-file', [AbsenceController::class, 'updateFile'])->name('abs.updateFile');

    /* Motivos */
    Route::get('absence/reason/search', [ReasonController::class, 'search'])->name('reason.search');
    Route::get('absence/reason/export/{texto?}', [ReasonController::class, 'export'])->name('reason.export');
    Route::resource('absence/reason', ReasonController::class);
    Route::put('absence/reason/change/{reason}', [ReasonController::class, 'change'])->name('reason.change');
    Route::get('/absences/download/{file}', function ($file) {
        $pathToFile = public_path('uploads/' . $file);
        if (file_exists($pathToFile)) {
            return Response::download($pathToFile);
        } else {
            abort(404, 'Archivo no encontrado.');
        }
    });

    /* Reportes */
    Route::get('report', [ReportController::class, 'index'])->name('report.index');
    Route::get('report/export/{texto?}', [ReportController::class, 'export'])->name('report.export');
    Route::get('report/search', [ReportController::class, 'search'])->name('report.search');

});
