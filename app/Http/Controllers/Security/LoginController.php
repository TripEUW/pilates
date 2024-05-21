<?php

namespace App\Http\Controllers\Security;

use App\Helpers\Pilates;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticatesUsers;
class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/dashboard';


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    return view('login');
    }

    public function username()
    {
        return 'email';
    }

    protected function authenticated(Request $request, $user)
    {

        if($user->status!="enable"){
     
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect('login')->withErrors(['error'=>'Tu cuenta ha sido deshabilitada']);
        }else{
            $time=Carbon::now()->format('H:i:s');
            /*auditoria: start*/Pilates::setAudit(false,"$time - usuario: $user->name $user->last_name - Login"); /*auditoria: end*/
        }
        
    }

    public function login(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        // try to login
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        return "El login fallo";
        return $this->sendFailedLoginResponse($request);
    }


   
}
