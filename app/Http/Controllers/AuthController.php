<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Image;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        }

        return redirect('login')->withErrors('Login details are not valid');
    }

    public function dashboard()
    {
        $images = Image::orderBy('created_at', 'desc')->get()->groupBy(function($date) {
            return \Carbon\Carbon::parse($date->created_at)->setTimezone('Asia/Jakarta')->format('Y-m-d'); // Mengelompokkan berdasarkan hari
        });

        return view('dashboard', compact('images'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

        return redirect('/login');
    }

}

