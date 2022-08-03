<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    public function index() {
        $providers = Provider::all();
        return view('app.providers.index')->with('providers', $providers);
    }
    
    public function loginProviderView() {
        return view('app.providers.login');
    }

    public function loginProvider(Request $request) {
        $credentials = $request->validate([
            'rfc' => ['required'],
            'password' => ['required'],
        ]);

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->intended('home');
        }
    }
}
