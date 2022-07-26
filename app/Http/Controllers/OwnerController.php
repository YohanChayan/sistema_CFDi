<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index() {
        $owners = Owner::all();
        return view('app.owners.index')->with('owners', $owners);
    }
}
