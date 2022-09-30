<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use RealRashid\SweetAlert\Facades\Alert;

class OwnerController extends Controller
{
    public function index() {
        $owners = Owner::where('status', 'A')->get();
        return view('app.admin.owners.index')->with('owners', $owners);
    }

    public function delete($id) {
        $owner = Owner::find($id);
        $owner -> status = 'I';
        $owner -> save();

        Alert::success('Éxito', 'Empresa eliminada correctamente');
        return redirect()->route('owners.index');
    }
}
