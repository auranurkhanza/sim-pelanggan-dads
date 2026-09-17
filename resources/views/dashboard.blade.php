<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Data Pelanggan - SIM Pelanggan DADS</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; height: 100vh; background-color: #f4f6f9; color: #333; }
        
        .sidebar { width: 250px; background-color: #1e293b; color: white; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; }
        .sidebar h2 { font-size: 18px; font-weight: bold; margin-bottom: 25px; color: #38bdf8; border-bottom: 1px solid #334155; padding-bottom: 12px; }
        .menu a { display: block; color: #cbd5e1; text-decoration: none; padding: 10px 14px; border-radius: 6px; margin-bottom: 6px; font-size: 14px; }
        .menu a:hover, .menu a.active { background-color: #0284c7; color: white; }
        .logout-btn { background-color: #ef4444; color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer; width: 100%; text-align: center; text-decoration: none; display: block; font-size: 14px; }
        
        .main-content { flex: 1; padding: 25px; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #0f172a; }
        .btn-add { background-color: #0284c7; color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; }
        
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px; }
        .card { background: white; padding: 18px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .card h3 { font-size: 13px; color: #64748b; margin-bottom: 8px; }
        .card .number { font-size: 22px; font-weight: bold; color: #0f172a; }
        
        .table-container { background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #f1f5f9; }
        th { background-color: #f8fafc; color: #475569; font-weight: 600; }
        
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .badge.valid { background-color: #dcfce7; color: #166534; }
        .badge.pending { background-color: #fef9c3; color: #854d0e; }
        .badge.invalid { background-color: #fee2e2; color: #991b1b; }
        .badge.sales { background-color: #e0f2fe; color: #0369a1; }
        
        .btn-action { padding: 6px 10px; border: none; border-radius: 4px; font-size: 12px; cursor: pointer; color: white; text-decoration: none; margin-right: 2px; }
        .btn-valid { background-color: #16a34a; }
        .btn-invalid { background-color: #dc2626; }
        .btn-delete { background-color: #64748b; }
        
        .alert-success { padding: 12px; background-color: #dcfce7; color: #166534; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
        .alert-danger { padding: 12px; background-color: #fee2e2; color: #991b1b; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
        
        /* Modal Form */
        .modal { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 25px; border-radius: 8px; width: 650px; max-height: 90vh; overflow-y: auto; }
        .modal-content h3 { margin-bottom: 15px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-size: 12px; font-weight: 600; margin-bottom: 4px; color: #475569; }
        .form-group input, .form-group select { padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; }
        .full-width { grid-column: span 2; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px; }
        .btn-cancel { background-color: #94a3b8; color: white; border: none; padding: 8px 14px; border-radius: 4px; cursor: pointer; }
        
        .form-section { display: none; margin-top: 10px; padding-top: 15px; border-top: 1px dashed #cbd5e1; grid-column: span 2; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div>
            <h2>SIM PELANGGAN DADS</h2>
            <div class="menu">
                <a href="/dashboard" class="active">Validasi Data (IKR)</a>
                <a href="/pelanggan">Semua Pelanggan</a>
                <a href="/laporan">Laporan Validasi</a>
            </div>
        </div>
        <a href="/login" class="logout-btn">Keluar / Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Validasi Data Pelanggan IKR</h1>
            <button class="btn-add" onclick="openModal()">+ Input Pelanggan Baru</button>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->has('error'))
            <div class="alert-danger">{{ $errors->first('error') }}</div>
        @endif

        <div class="cards">
            <div class="card">
                <h3>Total Pengajuan IKR</h3>
                <div class="number">{{ $pelanggan->where('pengisi', 'IKR')->count() }}</div>
            </div>
            <div class="card">
                <h3>Belum Divalidasi</h3>
                <div class="number" style="color: #d97706;">{{ $pelanggan->where('pengisi', 'IKR')->where('status_validasi', 'pending')->count() }}</div>
            </div>
            <div class="card">
                <h3>IKR Valid</h3>
                <div class="number" style="color: #16a34a;">{{ $pelanggan->where('pengisi', 'IKR')->where('status_validasi', 'valid')->count() }}</div>
            </div>
            <div class="card">
                <h3>IKR Tidak Valid</h3>
                <div class="number" style="color: #dc2626;">{{ $pelanggan->where('pengisi', 'IKR')->where('status_validasi', 'invalid')->count() }}</div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h3>Daftar Antrean Validasi IKR</h3>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>CID / Nama</th>
                        <th>Pengisi & Stasiun</th>
                        <th>Teknisi / Sales</th>
                        <th>Status Validasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pelanggan as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->cid ?? $item->nik ?? 'N/A' }}</strong><br>
                            <small>{{ $item->nama }} ({{ $item->no_hp ?? '-' }})</small>
                        </td>
                        <td>
                            <span class="badge" style="background:#e2e8f0; color:#334155;">{{ strtoupper($item->pengisi ?? '-') }}</span><br>
                            <small>{{ $item->stasiun ?? '-' }}</small>
                        </td>
                        <td>
                            @if($item->pengisi == 'IKR')
                                Teknisi: {{ $item->nama_teknisi ?? '-' }}
                            @else
                                Sales: {{ $item->pic_sales ?? '-' }}
                            @endif
                        </td>
                        <td>
                            @if($item->pengisi == 'IKR')
                                @if($item->status_validasi == 'pending')
                                    <span class="badge pending">Pending</span>
                                @elseif($item->status_validasi == 'valid')
                                    <span class="badge valid">Valid</span>
                                @else
                                    <span class="badge invalid">Tidak Valid</span>
                                @endif
                            @else
                                <span class="badge sales">Direct Sales</span>
                            @endif
                        </td>
                        <td>
                            @if($item->pengisi == 'IKR' && $item->status_validasi == 'pending')
                                <form action="/validasi/{{ $item->id }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="status" value="valid">
                                    <button type="submit" class="btn-action btn-valid">Setujui</button>
                                </form>
                                <form action="/validasi/{{ $item->id }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="status" value="invalid">
                                    <button type="submit" class="btn-action btn-invalid">Tolak</button>
                                </form>
                            @endif

                            <form action="/pelanggan/{{ $item->id }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form Input Dinamis -->
    <div class="modal" id="inputModal">
        <div class="modal-content">
            <h3>Input Data Pelanggan Baru</h3>
            
            <form action="/pelanggan/store" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    
                    <div class="form-group full-width">
                        <label>1. Pilih Tipe Pengisi Data</label>
                        <select name="pengisi" id="pengisiSelect" onchange="toggleFormByPengisi()" required>
                            <option value="">-- Pilih Tipe Pengisi --</option>
                            <option value="IKR">IKR (Teknisi - Perlu Validasi)</option>
                            <option value="Sales">Sales (Langsung Tersimpan)</option>
                        </select>
                    </div>

                    <!-- FORM KHUSUS IKR (14 Data) -->
                    <div id="formIKR" class="form-section">
                        <h4 style="color:#0284c7; margin-bottom:15px; grid-column: span 2;">Form Input Khusus IKR (Teknisi)</h4>
                        
                        <div class="form-group">
                            <label>2. Tanggal Aktivasi</label>
                            <input type="date" name="tanggal_aktivasi" class="ikr-input">
                        </div>
                        <div class="form-group">
                            <label>3. Stasiun</label>
                            <select name="stasiun" class="ikr-input">
                                <option value="">-- Pilih Stasiun --</option>
                                <option value="Tasikmalaya">Tasikmalaya</option><option value="Randuagung">Randuagung</option><option value="Garum">Garum</option><option value="Semarang Poncol">Semarang Poncol</option><option value="Mojokerto">Mojokerto</option><option value="Surabaya Gubeng">Surabaya Gubeng</option><option value="Malang">Malang</option><option value="Talun">Talun</option><option value="Kediri">Kediri</option><option value="Tulungagung">Tulungagung</option><option value="Jombang">Jombang</option><option value="Probolinggo">Probolinggo</option><option value="Wanaraja">Wanaraja</option><option value="Pasirjengkol">Pasirjengkol</option><option value="Wlingi">Wlingi</option><option value="Kepanjen">Kepanjen</option><option value="Kalioso">Kalioso</option><option value="Salem">Salem</option><option value="Sukoharjo">Sukoharjo</option><option value="Kertosono">Kertosono</option><option value="Jerakah">Jerakah</option><option value="Nganjuk">Nganjuk</option><option value="Gedebage">Gedebage</option><option value="Tarik">Tarik</option><option value="Sumbergempol">Sumbergempol</option><option value="Cicalengka">Cicalengka</option><option value="Sidoarjo">Sidoarjo</option><option value="Pakisaji - Malang Kota Lama">Pakisaji - Malang Kota Lama</option><option value="Sumberpucung - Ngebruk">Sumberpucung - Ngebruk</option><option value="Ngebruk - Kepanjen">Ngebruk - Kepanjen</option><option value="Kepanjen - Pakisaji">Kepanjen - Pakisaji</option><option value="Malang Kota Lama - Malang Kota Baru">Malang Kota Lama - Malang Kota Baru</option><option value="Wlingi - Kesamben">Wlingi - Kesamben</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>4. CID (Customer ID)</label>
                            <input type="text" name="cid" placeholder="Masukkan CID" class="ikr-input">
                        </div>
                        <div class="form-group">
                            <label>5. SN ONT</label>
                            <input type="text" name="sn_ont" placeholder="Masukkan SN ONT" class="ikr-input">
                        </div>
                        <div class="form-group">
                            <label>6. Nama Pelanggan</label>
                            <input type="text" name="nama" placeholder="Nama Pelanggan" class="ikr-input">
                        </div>
                        <div class="form-group">
                            <label>7. No HP</label>
                            <input type="text" name="no_hp" placeholder="Contoh: 08123456789" class="ikr-input">
                        </div>
                        <div class="form-group">
                            <label>8. Nama Teknisi (TIM)</label>
                            <input type="text" name="nama_teknisi" placeholder="Nama Teknisi/Tim" class="ikr-input">
                        </div>

                        <!-- DROPDOWN SUMBER WO -->
                        <div class="form-group">
                            <label>9. Sumber WO</label>
                            <select name="sumber_wo" class="ikr-input">
                                <option value="">-- Pilih Sumber WO --</option>
                                <option value="Door to Door">Door to Door</option>
                                <option value="Sales">Sales</option>
                                <option value="Program">Program</option>
                                <option value="Affiliate">Affiliate</option>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label>10. PIC Sales</label>
                            <input type="text" name="pic_sales" placeholder="Nama PIC Sales" class="ikr-input">
                        </div>
                        <div class="form-group">
                            <label>11. Foto KTP</label>
                            <input type="file" name="foto_ktp" accept="image/*" class="ikr-input">
                        </div>
                        <div class="form-group">
                            <label>12. Foto BAST Pelanggan</label>
                            <input type="file" name="foto_bast" accept="image/*" class="ikr-input">
                        </div>
                        <div class="form-group">
                            <label>13. Foto Pelanggan</label>
                            <input type="file" name="foto_pelanggan" accept="image/*" class="ikr-input">
                        </div>
                        <div class="form-group">
                            <label>14. Foto Bukti Transfer</label>
                            <input type="file" name="foto_bukti_transfer" accept="image/*" class="ikr-input">
                        </div>
                    </div>

                    <!-- FORM KHUSUS SALES (9 Data) -->
                    <div id="formSales" class="form-section">
                        <h4 style="color:#d97706; margin-bottom:15px; grid-column: span 2;">Form Input Khusus Sales</h4>
                        
                        <div class="form-group">
                            <label>2. Tanggal Aktivasi</label>
                            <input type="date" name="tanggal_aktivasi" class="sales-input">
                        </div>
                        <div class="form-group">
                            <label>3. Nama Sales</label>
                            <input type="text" name="pic_sales" placeholder="Nama Sales" class="sales-input">
                        </div>
                        <div class="form-group">
                            <label>4. Stasiun</label>
                            <select name="stasiun" class="sales-input">
                                <option value="">-- Pilih Stasiun --</option>
                                <option value="Tasikmalaya">Tasikmalaya</option><option value="Randuagung">Randuagung</option><option value="Garum">Garum</option><option value="Semarang Poncol">Semarang Poncol</option><option value="Mojokerto">Mojokerto</option><option value="Surabaya Gubeng">Surabaya Gubeng</option><option value="Malang">Malang</option><option value="Talun">Talun</option><option value="Kediri">Kediri</option><option value="Tulungagung">Tulungagung</option><option value="Jombang">Jombang</option><option value="Probolinggo">Probolinggo</option><option value="Wanaraja">Wanaraja</option><option value="Pasirjengkol">Pasirjengkol</option><option value="Wlingi">Wlingi</option><option value="Kepanjen">Kepanjen</option><option value="Kalioso">Kalioso</option><option value="Salem">Salem</option><option value="Sukoharjo">Sukoharjo</option><option value="Kertosono">Kertosono</option><option value="Jerakah">Jerakah</option><option value="Nganjuk">Nganjuk</option><option value="Gedebage">Gedebage</option><option value="Tarik">Tarik</option><option value="Sumbergempol">Sumbergempol</option><option value="Cicalengka">Cicalengka</option><option value="Sidoarjo">Sidoarjo</option><option value="Pakisaji - Malang Kota Lama">Pakisaji - Malang Kota Lama</option><option value="Sumberpucung - Ngebruk">Sumberpucung - Ngebruk</option><option value="Ngebruk - Kepanjen">Ngebruk - Kepanjen</option><option value="Kepanjen - Pakisaji">Kepanjen - Pakisaji</option><option value="Malang Kota Lama - Malang Kota Baru">Malang Kota Lama - Malang Kota Baru</option><option value="Wlingi - Kesamben">Wlingi - Kesamben</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>5. Nama Pelanggan</label>
                            <input type="text" name="nama" placeholder="Nama Pelanggan" class="sales-input">
                        </div>
                        <div class="form-group">
                            <label>6. NIK Pelanggan</label>
                            <input type="text" name="nik" placeholder="Masukkan NIK 16 Digit" class="sales-input">
                        </div>
                        <div class="form-group">
                            <label>7. No HP</label>
                            <input type="text" name="no_hp" placeholder="Contoh: 08123456789" class="sales-input">
                        </div>
                        <div class="form-group">
                            <label>8. Foto KTP</label>
                            <input type="file" name="foto_ktp" accept="image/*" class="sales-input">
                        </div>
                        <div class="form-group">
                            <label>9. Foto Bukti Transfer</label>
                            <input type="file" name="foto_bukti_transfer" accept="image/*" class="sales-input">
                        </div>
                    </div>

                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-add" id="btnSubmit" style="display:none;">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() { 
            document.getElementById('inputModal').style.display = 'flex'; 
            toggleFormByPengisi();
        }
        function closeModal() { 
            document.getElementById('inputModal').style.display = 'none'; 
        }

        function toggleFormByPengisi() {
            var selected = document.getElementById('pengisiSelect').value;
            var formIKR = document.getElementById('formIKR');
            var formSales = document.getElementById('formSales');
            var btnSubmit = document.getElementById('btnSubmit');
            
            var inputsIKR = formIKR.querySelectorAll('.ikr-input');
            var inputsSales = formSales.querySelectorAll('.sales-input');

            formIKR.style.display = 'none';
            formSales.style.display = 'none';
            btnSubmit.style.display = 'none';
            inputsIKR.forEach(el => el.disabled = true);
            inputsSales.forEach(el => el.disabled = true);

            if (selected === 'IKR') {
                formIKR.style.display = 'grid';
                btnSubmit.style.display = 'block';
                inputsIKR.forEach(el => { el.disabled = false; el.required = (el.type !== 'file'); });
            } 
            else if (selected === 'Sales') {
                formSales.style.display = 'grid';
                btnSubmit.style.display = 'block';
                inputsSales.forEach(el => { el.disabled = false; el.required = (el.type !== 'file'); });
            }
        }
    </script>

</body>
</html>
