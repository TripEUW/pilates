<?php

namespace App\Http\Controllers\Security;

use App\Helpers\Pilates;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Laravel\Fortify\Contracts\TwoFactorAuthenticatable;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';
    use TwoFactorAuthenticatable;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Display the login form.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        Fortify::loginView(function () {
            return view('/login');
        });
    }

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
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {

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
        } else {
        }

    }

    /**
     * Handle the login request.
     *
     * @param LoginRequest $request The login request object.
     * @param EnsureLoginIsNotThrottled $ensureLoginIsNotThrottled The throttling handler.
     * @param RedirectIfTwoFactorAuthenticatable $redirectIfTwoFactorAuthenticatable The two-factor authentication handler.
     * @param AttemptToAuthenticate $attemptToAuthenticate The authentication attempt handler.
     * @return mixed
     */
    public function login(LoginRequest $request, EnsureLoginIsNotThrottled $ensureLoginIsNotThrottled, RedirectIfTwoFactorAuthenticatable $redirectIfTwoFactorAuthenticatable, AttemptToAuthenticate $attemptToAuthenticate)
    {
        $ensureLoginIsNotThrottled($request);

        $attemptToAuthenticate($request, [Fortify::username() => $request->{Fortify::username()}, 'password' => $request->password]);

        return $redirectIfTwoFactorAuthenticatable($request, config('fortify.home'));
    }
    /**
     * Logout the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
