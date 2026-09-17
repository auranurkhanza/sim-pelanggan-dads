<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
});

Route::get('/dashboard', function () {
    $pelanggan = DB::table('pelanggan')->orderBy('id', 'desc')->get();
    return view('dashboard', compact('pelanggan'));
})->middleware('auth');

// Route untuk menyimpan data pelanggan baru (input oleh Sales/Teknisi)
Route::post('/pelanggan/store', function (Request $request) {
    DB::table('pelanggan')->insert([
        'nik' => $request->input('nik'),
        'nama' => $request->input('nama'),
        'alamat' => $request->input('alamat'),
        'status_validasi' => 'pending',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return back()->with('success', 'Data pelanggan berhasil ditambahkan!');
})->middleware('auth');

// Route untuk aksi tombol Setujui / Tolak
Route::post('/validasi/{id}', function (Request $request, $id) {
    $status = $request->input('status');
    
    DB::table('pelanggan')->where('id', $id)->update([
        'status_validasi' => $status,
        'updated_at' => now()
    ]);

    return back()->with('success', 'Status validasi berhasil diperbarui!');
})->middleware('auth');
