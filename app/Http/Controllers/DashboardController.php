<?php

namespace App\Http\Controllers;

use App\Models\Assist;
use App\Models\Pdv;
use App\Models\Worker;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{

    public function index()
    {
        $workers = Worker::where('status', 1)->get();
        $pdvs = Pdv::where('status', 1)->get();
        $today = date('Y-m-d');
        $Assists = Assist::where('current_date', $today)->get();
        $tardinessCount = Assist::where('current_date', $today)
                                 ->where('status_entry', 'tarde')
                                 ->count();

        return Inertia::render('Dashboard', compact('workers', 'pdvs', 'Assists', 'tardinessCount'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
