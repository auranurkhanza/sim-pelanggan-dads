<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return redirect('/login');
});

// ROUTE LOGIN
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

// ROUTE REGISTER (PENDAFTARAN AKUN BARU)
Route::get('/register', function () {
    return view('register');
});

Route::post('/register', function (Request $request) {
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
    ], [
        'email.unique' => 'Email ini sudah terdaftar, silakan gunakan email lain atau login.',
        'password.min' => 'Password minimal harus 6 karakter.'
    ]);

    DB::table('users')->insert([
        'name'       => $request->input('name'),
        'email'      => $request->input('email'),
        'password'   => Hash::make($request->input('password')),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect('/login')->with('success', 'Akun berhasil dibuat! Silakan login.');
});

// ROUTE DASHBOARD (Dengan Filter, Search & Sort)
Route::get('/dashboard', function (Request $request) {
    $query = DB::table('pelanggan');

    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('cid', 'like', "%{$search}%")
              ->orWhere('nik', 'like', "%{$search}%");
        });
    }

    if ($request->filled('status')) {
        $query->where('status_validasi', $request->input('status'));
    }

    if ($request->filled('stasiun')) {
        $query->where('stasiun', $request->input('stasiun'));
    }

    $sort = $request->input('sort', 'latest');
    if ($sort === 'oldest') {
        $query->orderBy('id', 'asc');
    } elseif ($sort === 'name_asc') {
        $query->orderBy('nama', 'asc');
    } elseif ($sort === 'name_desc') {
        $query->orderBy('nama', 'desc');
    } else {
        $query->orderBy('id', 'desc');
    }

    $pelanggan = $query->get();

    return view('dashboard', compact('pelanggan'));
})->middleware('auth');

// ROUTE SEMUA PELANGGAN (Dengan Filter, Search & Sort)
Route::get('/pelanggan', function (Request $request) {
    $query = DB::table('pelanggan');

    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('cid', 'like', "%{$search}%")
              ->orWhere('nik', 'like', "%{$search}%");
        });
    }

    if ($request->filled('status')) {
        $query->where('status_validasi', $request->input('status'));
    }

    if ($request->filled('stasiun')) {
        $query->where('stasiun', $request->input('stasiun'));
    }

    $sort = $request->input('sort', 'latest');
    if ($sort === 'oldest') {
        $query->orderBy('id', 'asc');
    } elseif ($sort === 'name_asc') {
        $query->orderBy('nama', 'asc');
    } elseif ($sort === 'name_desc') {
        $query->orderBy('nama', 'desc');
    } else {
        $query->orderBy('id', 'desc');
    }

    $pelanggan = $query->get();

    return view('pelanggan', compact('pelanggan'));
})->middleware('auth');

Route::get('/laporan', function () {
    $pelanggan = DB::table('pelanggan')->orderBy('id', 'desc')->get();
    return view('laporan', compact('pelanggan'));
})->middleware('auth');

// FUNGSI OCR MEMBACA TEKS GAMBAR
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

// ROUTE SIMPAN DATA PELANGGAN
Route::post('/pelanggan/store', function (Request $request) {
    $pengisi = $request->input('pengisi');
    $namaInput = strtoupper(trim($request->input('nama')));
    $retryCount = (int) $request->input('retry_count', 0);
    $remark = null;

    if ($pengisi === 'IKR') {
        if (!$request->hasFile('foto_ktp') || !$request->hasFile('foto_bast')) {
            return back()->withInput()->withErrors([
                'error' => 'Gagal submit! Foto KTP dan Foto BAST wajib diunggah untuk verifikasi.'
            ]);
        }

        $teksKtp = ocrReadImage($request->file('foto_ktp'));
        $ktpMatched = str_contains($teksKtp, $namaInput);

        $teksBast = ocrReadImage($request->file('foto_bast'));
        $bastMatched = str_contains($teksBast, $namaInput);

        $isValid = ($ktpMatched && $bastMatched);

        if (!$isValid) {
            $alasan = [];
            if (!$ktpMatched) $alasan[] = 'Nama tidak ditemukan pada Foto KTP';
            if (!$bastMatched) $alasan[] = 'Nama tidak ditemukan pada Foto BAST';
            $teksAlasan = implode(' & ', $alasan);

            if ($retryCount < 1) {
                return back()->withInput([
                    'pengisi' => 'IKR',
                    'nama' => $request->input('nama'),
                    'tanggal_aktivasi' => $request->input('tanggal_aktivasi'),
                    'stasiun' => $request->input('stasiun'),
                    'cid' => $request->input('cid'),
                    'sn_ont' => $request->input('sn_ont'),
                    'no_hp' => $request->input('no_hp'),
                    'nama_teknisi' => $request->input('nama_teknisi'),
                    'sumber_wo' => $request->input('sumber_wo'),
                    'pic_sales' => $request->input('pic_sales'),
                    'retry_count' => 1
                ])->withErrors([
                    'error' => 'Validasi Gagal (Percobaan 1): ' . $teksAlasan . '. Silakan periksa nama atau unggah ulang foto yang lebih jelas!'
                ]);
            }

            $statusValidasi = 'pending';
            $remark = 'Gagal Auto-Validasi: ' . $teksAlasan;
            $pesan = 'Data tersimpan ke antrean PENDING (Butuh Revisi) karena validasi foto 2x tidak cocok.';
        } else {
            $statusValidasi = 'valid';
            $remark = 'Sesuai (Auto-Validated BY OCR)';
            $pesan = 'Data Pelanggan Ter-Validasi Otomatis dan Berhasil Disimpan!';
        }
    } else {
        $statusValidasi = 'valid';
        $remark = 'Direct Sales';
        $pesan = 'Data Sales Berhasil Disimpan!';
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
        'remark'             => $remark,
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

    return back()->with('success', $pesan);
})->middleware('auth');

// ROUTE VALIDASI MANUAL ADMIN
Route::post('/validasi/{id}', function (Request $request, $id) {
    $status = $request->input('status');
    $remarkInput = $request->input('remark');
    
    DB::table('pelanggan')->where('id', $id)->update([
        'status_validasi' => $status,
        'remark'          => $remarkInput ?? ($status === 'valid' ? 'Disetujui Admin' : 'Ditolak Admin'),
        'updated_at'      => now()
    ]);

    return back()->with('success', 'Status validasi berhasil diperbarui!');
})->middleware('auth');

// ROUTE EXPORT EXCEL
Route::get('/pelanggan/export', function () {
    $fileName = 'data_pelanggan_' . date('Y-m-d_H-i-s') . '.csv';
    $pelanggan = DB::table('pelanggan')->orderBy('id', 'desc')->get();

    $headers = array(
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    );

    $columns = array('ID', 'Tipe Pengisi', 'Tgl Aktivasi', 'Stasiun', 'CID', 'SN ONT', 'Nama Pelanggan', 'NIK', 'No HP', 'Nama Teknisi', 'Sumber WO', 'PIC Sales', 'Status Validasi', 'Catatan / Remark');

    $callback = function() use($pelanggan, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($pelanggan as $item) {
            fputcsv($file, array(
                $item->id, $item->pengisi, $item->tanggal_aktivasi, $item->stasiun,
                $item->cid, $item->sn_ont, $item->nama, $item->nik,
                $item->no_hp, $item->nama_teknisi, $item->sumber_wo, $item->pic_sales,
                $item->status_validasi, $item->remark
            ));
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
})->middleware('auth');

// ROUTE HAPUS
Route::delete('/pelanggan/{id}', function ($id) {
    DB::table('pelanggan')->where('id', $id)->delete();
    return back()->with('success', 'Data pelanggan berhasil dihapus!');
})->middleware('auth');
