<?php

namespace App\Http\Controllers\Security;

use App\Helpers\Pilates;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Notifications\TwoFactorCode;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Http\Requests\LoginRequest;
// use Laravel\Fortify\Contracts\TwoFactorAuthenticatable;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';
    // use TwoFactorAuthenticatable;
    use AuthenticatesUsers;
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Display the login form.
     *
     * @return \Illuminate\Contracts\View\View
     */
    

    /**
     * Get the username for the login process.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Handle the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Employee $user
     * @return \Illuminate\Http\RedirectResponse
     */
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
    public function login(Request $request){
        dd('here');
        Log::log('info', 'login');
        return redirect()->route('home');
        // if(auth()->check()){
        // }
    }
   
    /**
     * Logout the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function logout(Request $request)
    {
        Log::log('info', 'logout');

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

