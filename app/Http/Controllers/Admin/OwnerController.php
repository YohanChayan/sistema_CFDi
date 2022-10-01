<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class OwnerController extends Controller
{
    public function index() {
        $owners = Owner::where('status', 'A')->get();
        return view('app.admin.owners.index')->with('owners', $owners);
    }

    public function store(Request $request) {
        $data = $request->all();

        $owner = new Owner();
        $owner -> rfc = mb_strtoupper($data['rfc']);
        $owner -> nombre = mb_strtoupper($data['name']);
        $owner -> save();

        Alert::success('Éxito', 'Empresa registrada correctamente');
        return redirect()->route('owners.index');
    }

    public function delete($id) {
        $owner = Owner::find($id);
        $owner -> status = 'I';
        $owner -> save();

        Alert::success('Éxito', 'Empresa eliminada correctamente');
        return redirect()->route('owners.index');
    }
}
