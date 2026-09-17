<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

// Route untuk menampilkan halaman login
Route::get('/login', function () {
    return view('login');
})->name('login');

// Route untuk memproses data login (POST)
Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    // Cek autentikasi user
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return "Berhasil Login! Selamat datang di SIM Pelanggan DADS.";
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
});
