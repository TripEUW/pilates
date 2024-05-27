<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\TwoFactorCode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class TwoFactorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'twofactor']);
    }

    public function index()
    {
        return view('auth.twoFactor');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'two_factor_code' => 'integer|required',
            ]);

            $user = auth()->user();

            if ($request->input('two_factor_code') == $user->two_factor_code) {
                $user->resetTwoFactorCode();

                return redirect('/');
            }

            return redirect()->back()->withErrors(['two_factor_code' => 'The two factor code you have entered does not match']);
        } catch (Exception $e) {
            Log::error('An error occurred with user ID ' . $user->id . ': ' . $e->getMessage());

            // You can choose to redirect back with the error message or to a dedicated error page
            return redirect()->back()->withErrors(['error' => 'An error occurred while validating the two factor code.']);
        }
    }

    public function resend()
    {
        $user = auth()->user();
        $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCode());

        return redirect()->back()->withMessage('The two factor code has been sent again');
    }
}