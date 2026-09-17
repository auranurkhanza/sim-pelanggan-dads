<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Route simpan dengan logika Auto-Validation Nama
Route::post('/pelanggan/store', function (Request $request) {
    $pengisi = $request->input('pengisi');
    $namaInput = strtolower(trim($request->input('nama')));
    
    // Default Status Validasi
    $statusValidasi = 'pending';

    // -------------------------------------------------------------
    // LOGIKA AUTO-VALIDATION NAMA (Jika Pengisi IKR)
    // -------------------------------------------------------------
    if ($pengisi === 'IKR') {
        $namaKtpSesuai = true;
        $namaBastSesuai = true;

        // 1. Cek apakah ada file Foto KTP & BAST
        // (Di tingkat lanjut, teks $namaInput dicocokkan dengan hasil scan OCR foto)
        if ($request->hasFile('foto_ktp') && $request->hasFile('foto_bast')) {
            // Contoh aturan auto-validation:
            // Jika Nama Pelanggan diisi dengan benar (tidak kosong & min 3 karakter)
            if (strlen($namaInput) >= 3) {
                $statusValidasi = 'valid'; // AUTO-VALIDATED!
            } else {
                $statusValidasi = 'invalid';
            }
        } else {
            // Jika berkas foto tidak lengkap
            $statusValidasi = 'pending';
        }
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

    // Simpan File Upload Foto
    $fotoFields = ['foto_ktp', 'foto_bast', 'foto_pelanggan', 'foto_bukti_transfer'];
    foreach ($fotoFields as $field) {
        if ($request->hasFile($field)) {
            $path = $request->file($field)->store('uploads', 'public');
            $data[$field] = $path;
        }
    }

    DB::table('pelanggan')->insert($data);

    $pesan = ($statusValidasi === 'valid') 
        ? 'Data Berhasil Diinput & Ter-VALIDASI Otomatis oleh Sistem!' 
        : 'Data Berhasil Diinput (Menunggu Verifikasi Manual Admin).';

    return back()->with('success', $pesan);
})->middleware('auth');
