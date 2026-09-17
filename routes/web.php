<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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

    return back()->withErrors(['email' => 'Email atau password salah.']);
});

Route::get('/dashboard', function () {
    $pelanggan = DB::table('pelanggan')->orderBy('id', 'desc')->get();
    return view('dashboard', compact('pelanggan'));
})->middleware('auth');

Route::get('/pelanggan', function () {
    $pelanggan = DB::table('pelanggan')->orderBy('id', 'desc')->get();
    return view('pelanggan', compact('pelanggan'));
})->middleware('auth');

Route::get('/laporan', function () {
    $pelanggan = DB::table('pelanggan')->orderBy('id', 'desc')->get();
    return view('laporan', compact('pelanggan'));
})->middleware('auth');

// FUNGSI BANTUAN OCR UNTUK MEMBACA TEKS DARI FOTO
function ocrReadImage($file) {
    try {
        $response = Http::attach(
            'file', file_get_contents($file->path()), $file->getClientOriginalName()
        )->post('https://api.ocr.space/parse/image', [
            'apikey' => 'K86792934788957', // API Key OCR.space
            'language' => 'eng',
            'isOverlayRequired' => 'false'
        ]);

        $result = $response->json();
        if (isset($result['ParsedResults'][0]['ParsedText'])) {
            return strtoupper($result['ParsedResults'][0]['ParsedText']);
        }
    } catch (\Exception $e) {
        return '';
    }
    return '';
}

// ROUTE SIMPAN DENGAN AUTO-VALIDASI OCR (KTP & BAST)
Route::post('/pelanggan/store', function (Request $request) {
    $pengisi = $request->input('pengisi');
    $namaInput = strtoupper(trim($request->input('nama')));
    
    $statusValidasi = 'pending';
    $catatanValidasi = '';

    // Lakukan OCR hanya jika tipe pengisi = IKR
    if ($pengisi === 'IKR') {
        $ktpMatched = false;
        $bastMatched = false;

        // 1. Scan Foto KTP
        if ($request->hasFile('foto_ktp')) {
            $teksKtp = ocrReadImage($request->file('foto_ktp'));
            if (str_contains($teksKtp, $namaInput)) {
                $ktpMatched = true;
            }
        }

        // 2. Scan Foto BAST
        if ($request->hasFile('foto_bast')) {
            $teksBast = ocrReadImage($request->file('foto_bast'));
            if (str_contains($teksBast, $namaInput)) {
                $bastMatched = true;
            }
        }

        // 3. Evaluasi Hasil Pencocokan Nama
        if ($ktpMatched && $bastMatched) {
            $statusValidasi = 'valid'; // Keduanya Cocok Otomatis Valid!
            $catatanValidasi = 'Sistem Auto-Validasi: Nama sesuai pada KTP dan BAST.';
        } elseif ($ktpMatched || $bastMatched) {
            $statusValidasi = 'pending';
            $catatanValidasi = 'Sistem Auto-Validasi: Nama hanya ditemukan di salah satu berkas.';
        } else {
            $statusValidasi = 'invalid';
            $catatanValidasi = 'Sistem Auto-Validasi: Nama tidak ditemukan pada Foto KTP & BAST.';
        }
    } else {
        // Sales otomatis Valid
        $statusValidasi = 'valid';
        $catatanValidasi = 'Input Sales Direct';
    }

    $data = [
        'pengisi'            => $pengisi,
        'tanggal_aktivasi'   => $request->input('tanggal_aktivasi'),
        'stasiun'            => $request->input('stasiun'),
        'cid'                => $request->input('cid'),
        'sn_ont'             => $request->input('sn_ont'),
        'nama'               => $request->input('nama'),
        'nik'                => $request->input('nik') ?? '-',
        'no_hp'              => $request->input('no_hp'),
        'nama_teknisi'       => $request->input('nama_teknisi'),
        'sumber_wo'          => $request->input('sumber_wo'),
        'pic_sales'          => $request->input('pic_sales'),
        'status_validasi'    => $statusValidasi,
        'created_at'         => now(),
        'updated_at'         => now(),
    ];

    $fotoFields = ['foto_ktp', 'foto_bast', 'foto_pelanggan', 'foto_bukti_transfer'];
    foreach ($fotoFields as $field) {
        if ($request->hasFile($field)) {
            $path = $request->file($field)->store('uploads', 'public');
            $data[$field] = $path;
        }
    }

    DB::table('pelanggan')->insert($data);

    return back()->with('success', 'Data Pelanggan Berhasil Diproses! ' . $catatanValidasi);
})->middleware('auth');

Route::post('/validasi/{id}', function (Request $request, $id) {
    $status = $request->input('status');
    DB::table('pelanggan')->where('id', $id)->update([
        'status_validasi' => $status,
        'updated_at' => now()
    ]);
    return back()->with('success', 'Status validasi berhasil diperbarui!');
})->middleware('auth');

Route::delete('/pelanggan/{id}', function ($id) {
    DB::table('pelanggan')->where('id', $id)->delete();
    return back()->with('success', 'Data pelanggan berhasil dihapus!');
})->middleware('auth');
