<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

class EmployeeForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('guest:employee');
    }

    protected function broker(){
        return Password::broker('employees');
    }

    
    public function showLinkRequestForm()
    {
     
        return view('login');
    }
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
                ->withInput($request->only('email','resetForm'))
                ->withErrors(['email' => trans($response)]);
    }



  
}
