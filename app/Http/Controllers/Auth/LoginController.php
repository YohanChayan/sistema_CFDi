<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = 'administrador/';
    protected $redirectTo = '/dashboard';
    // protected $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        $is_identityEmail = request()->input('email');
        $is_identityRFC = request()->input('rfc');

        // Revisa los 2 valores dependiendo de que POST viene: (login de proveedores o login de administradores)
        $identity = is_null($is_identityEmail) ? $is_identityRFC : $is_identityEmail;

        $fieldName = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'rfc';

        request()->merge([$fieldName => $identity]);
        return $fieldName;
    }

    // protected function sendFailedLoginResponse(Request $request)
    // {
    //     $request->session()->flash('login_error', trans('auth.failed'));
    //     throw ValidationException::withMessages(
    //         [
    //             'login_error' => [trans('auth.failed')],
    //         ]
    //     );
    // }





}
