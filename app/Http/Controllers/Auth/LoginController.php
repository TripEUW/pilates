<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Pilates;
use App\Http\Controllers\Controller;
use App\Notifications\TwoFactorCode;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


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
    protected $redirectTo = RouteServiceProvider::HOME;
    protected function authenticated(Request $request, $user)
    {
        $user->generateTwoFactorCode();
        Log::log('info', 'generated');
        $user->notify(new TwoFactorCode());
        if (is_object($user)) {
            if ($user->status != 'enable') {
                $this->guard()->logout();
                $request->session()->invalidate();
                return redirect('login')->withErrors(['error' => 'Tu cuenta ha sido deshabilitada']);
            } else {
                $time = Carbon::now()->format('H:i:s');
                /*auditoria: start*/
                Pilates::setAudit(false, "$time - usuario: $user->name $user->last_name - Login"); /*auditoria: end*/
            }
        }
        return redirect('/');
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
