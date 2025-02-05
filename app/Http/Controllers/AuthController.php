<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 401);
    }

    public function checkAuth(Request $request)
    {
        return response()->json(['authenticated' => Auth::check()]);
    }
}
