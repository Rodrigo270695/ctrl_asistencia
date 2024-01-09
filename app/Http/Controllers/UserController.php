<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Http\Requests\UserRequest;
use App\Models\Pdv;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

use function Laravel\Prompts\alert;

class UserController extends Controller
{

    public function index()
    {
        $users = User::with(['roles','pdv'])->orderBy('id', 'desc')->paginate(7);
        $pdvs = Pdv::where('status', 1)->get();

        return Inertia::render('User/Index', compact('users','pdvs'));
    }

    public function create()
    {
        //
    }

    public function store(UserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->rol),
                'pdv_id' => Pdv::where('spot', $request->pdv)->first()->id,
            ]);

            $user->assignRole($request->rol);

            return redirect()->route('user.index')->with('toast', ['Usuario creado exitosamente!','success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El usuario ya existe!','warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function update(UserRequest $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->rol),
                'pdv_id' => Pdv::where('spot', $request->pdv)->first()->id,
            ]);

            $user->syncRoles($request->rol);

            return redirect()->route('user.index')->with('toast', ['Usuario actualizado exitosamente!','success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El usuario ya existe!','warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('user.index')->with('toast', ['Usuario eliminado exitosamente!','success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
        }
    }

    public function search(Request $request)
    {
        $texto = $request->get('texto');
        $users = User::where('name', 'LIKE', "%{$texto}%")
            ->orWhere('email', 'LIKE', "%{$texto}%")
            ->orWhereHas('pdv', function ($q) use ($texto) {
                $q->where('spot', 'LIKE', "%{$texto}%");
            })
            ->orWhereHas('roles', function ($q) use ($texto) {
                $q->where('name', 'LIKE', "%{$texto}%");
            })
            ->with(['roles','pdv'])
            ->paginate(10);

        return Inertia::render('User/Index', compact('users', 'texto'));
    }

    public function export($texto = null)
    {
        $filename = 'usuarios_' . now()->format('d-m-Y_H-i') . '.xlsx';
        return Excel::download(new UsersExport($texto), $filename);
    }

}
