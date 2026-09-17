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

// Route Simpan Pelanggan Baru dengan 14 Field & Upload Foto
Route::post('/pelanggan/store', function (Request $request) {
    $data = [
        'pengisi'            => $request->input('pengisi'),
        'tanggal_aktivasi'   => $request->input('tanggal_aktivasi'),
        'stasiun'            => $request->input('stasiun'),
        'cid'                => $request->input('cid'),
        'sn_ont'             => $request->input('sn_ont'),
        'nama'               => $request->input('nama'),
        'no_hp'              => $request->input('no_hp'),
        'nama_teknisi'       => $request->input('nama_teknisi'),
        'sumber_wo'          => $request->input('sumber_wo'),
        'pic_sales'          => $request->input('pic_sales'),
        'status_validasi'    => 'pending',
        'created_at'         => now(),
        'updated_at'         => now(),
    ];

    // Proses Simpan File Foto (jika diupload)
    $fotoFields = ['foto_ktp', 'foto_bast', 'foto_pelanggan', 'foto_bukti_transfer'];
    foreach ($fotoFields as $field) {
        if ($request->hasFile($field)) {
            $path = $request->file($field)->store('uploads', 'public');
            $data[$field] = $path;
        }
    }

    DB::table('pelanggan')->insert($data);

    return back()->with('success', 'Data pelanggan berhasil dikirim untuk divalidasi!');
})->middleware('auth');

// Route Aksi Setujui / Tolak
Route::post('/validasi/{id}', function (Request $request, $id) {
    $status = $request->input('status');
    
    DB::table('pelanggan')->where('id', $id)->update([
        'status_validasi' => $status,
        'updated_at' => now()
    ]);

    return back()->with('success', 'Status validasi berhasil diperbarui!');
})->middleware('auth');
