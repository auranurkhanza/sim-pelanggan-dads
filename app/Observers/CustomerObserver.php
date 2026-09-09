<?php

namespace App\Observers;

use App\Models\Customer;

class CustomerObserver
{
    public function saving(Customer $customer): void
    {
        // 1. Cek Kelengkapan Dokumen Fisik
        $missingFiles = [];
        if (empty($customer->ktp_path)) $missingFiles[] = 'Foto KTP';
        if (empty($customer->bast_path)) $missingFiles[] = 'Foto BAST';
        if (empty($customer->customer_photo_path)) $missingFiles[] = 'Foto Pelanggan';

        if (!empty($missingFiles)) {
            $customer->status = 'belum_lengkap';
            $customer->validation_notes = 'Dokumen belum lengkap. Harap unggah: ' . implode(', ', $missingFiles) . '.';
            return;
        }

        // 2. Cek Kesesuaian & Validitas Format Data Pelanggan
        $validationErrors = [];

        // Validasi Nomor Telepon (minimal 10 digit, hanya angka)
        $cleanPhone = preg_replace('/[^0-9]/', '', $customer->phone_number);
        if (strlen($cleanPhone) < 10) {
            $validationErrors[] = 'Nomor telepon kurang dari 10 digit';
        }

        // Validasi Format File Gambar (Harus JPG / JPEG / PNG)
        $validExtensions = ['jpg', 'jpeg', 'png'];
        
        $ktpExt = strtolower(pathinfo($customer->ktp_path, PATHINFO_EXTENSION));
        if (!in_array($ktpExt, $validExtensions)) {
            $validationErrors[] = 'Format Foto KTP harus berupa JPG/PNG';
        }

        $bastExt = strtolower(pathinfo($customer->bast_path, PATHINFO_EXTENSION));
        if (!in_array($bastExt, $validExtensions)) {
            $validationErrors[] = 'Format Foto BAST harus berupa JPG/PNG';
        }

        // 3. Keputusan Validasi Otomatis oleh Sistem
        if (!empty($validationErrors)) {
            $customer->status = 'perlu_diperbaiki';
            $customer->validation_notes = 'Pemeriksaan sistem menemukan kesalahan: ' . implode(', ', $validationErrors) . '.';
        } else {
            $customer->status = 'valid';
            $customer->validation_notes = 'Pemeriksaan sistem otomatis BERHASIL: Seluruh berkas (KTP, BAST, Foto Pelanggan) lengkap dan format data sesuai.';
        }
    }
}