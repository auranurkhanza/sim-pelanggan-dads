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
        
        // DIUBAH DI SINI:
        // Mengarahkan ke halaman dashboard/utama setelah berhasil login
        return redirect()->intended('/dashboard'); 
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
});

// Route halaman dashboard (contoh)
Route::get('/dashboard', function () {
    return view('dashboard'); // atau return "Selamat Datang di Dashboard SIM Pelanggan DADS";
})->middleware('auth');
