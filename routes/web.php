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

// FUNGSI OCR UNTUK MEMBACA TEKS DARI GAMBAR
function ocrReadImage($file) {
    try {
        $response = Http::attach(
            'file', file_get_contents($file->path()), $file->getClientOriginalName()
        )->post('https://api.ocr.space/parse/image', [
            'apikey' => 'K86792934788957',
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

// ROUTE SIMPAN KETAT (JIKA TIDAK VALID -> BLOKIR SUBMIT)
Route::post('/pelanggan/store', function (Request $request) {
    $pengisi = $request->input('pengisi');
    $namaInput = strtoupper(trim($request->input('nama')));

    // VALIDASI KHUSUS IKR
    if ($pengisi === 'IKR') {
        // Pastikan berkas foto KTP & BAST diunggah
        if (!$request->hasFile('foto_ktp') || !$request->hasFile('foto_bast')) {
            return back()->withInput()->withErrors([
                'error' => 'Gagal submit! Foto KTP dan Foto BAST wajib diunggah untuk verifikasi IKR.'
            ]);
        }

        // 1. Scan Foto KTP
        $teksKtp = ocrReadImage($request->file('foto_ktp'));
        $ktpMatched = str_contains($teksKtp, $namaInput);

        // 2. Scan Foto BAST
        $teksBast = ocrReadImage($request->file('foto_bast'));
        $bastMatched = str_contains($teksBast, $namaInput);

        // JIKA NAMA TIDAK COCOK DI KTP ATAU BAST -> TOLAK / BLOKIR SUBMIT
        if (!$ktpMatched || !$bastMatched) {
            $alasan = [];
            if (!$ktpMatched) $alasan[] = 'Nama tidak ditemukan pada Foto KTP';
            if (!$bastMatched) $alasan[] = 'Nama tidak ditemukan pada Foto BAST';

            return back()->withInput()->withErrors([
                'error' => 'Gagal Submit! Data Tidak Valid: ' . implode(' & ', $alasan) . '. Pastikan nama pelanggan sesuai dengan foto yang diunggah!'
            ]);
        }

        $statusValidasi = 'valid';
    } else {
        // Sales otomatis Valid
        $statusValidasi = 'valid';
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

    return back()->with('success', 'Data Pelanggan Valid dan Berhasil Disimpan!');
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
