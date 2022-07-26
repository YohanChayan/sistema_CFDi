<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index() {
        $providers = Provider::all();
        return view('app.providers.index')->with('providers', $providers);
    }
}
