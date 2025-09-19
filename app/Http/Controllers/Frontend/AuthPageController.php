<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthPageController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function saveToken(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        session(['jwt' => $request->token]);
        return response()->json(['message' => 'Token saved to session']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('jwt');

        // If you’re also using Laravel’s native auth:
        // auth()->logout();

        return redirect('/'); 
    }

}
