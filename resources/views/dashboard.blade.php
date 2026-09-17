<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Data - SIVALID STARLITE DADS</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; height: 100vh; background-color: #f4f6f9; color: #333; }
        
        .sidebar { width: 250px; background-color: #1e293b; color: white; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; }
        .sidebar h2 { font-size: 16px; font-weight: bold; margin-bottom: 25px; color: #38bdf8; border-bottom: 1px solid #334155; padding-bottom: 12px; letter-spacing: 0.5px; }
        .menu a { display: block; color: #cbd5e1; text-decoration: none; padding: 10px 14px; border-radius: 6px; margin-bottom: 6px; font-size: 14px; }
        .menu a:hover, .menu a.active { background-color: #0284c7; color: white; }
        .logout-btn { background-color: #ef4444; color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer; width: 100%; text-align: center; text-decoration: none; display: block; font-size: 14px; }
        
        .main-content { flex: 1; padding: 25px; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #0f172a; }
        .btn-add { background-color: #0284c7; color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; text-decoration: none; display: inline-block; }
        .btn-export { background-color: #16a34a; color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; text-decoration: none; margin-right: 8px; display: inline-block; }
        
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
            <h2>SIVALID STARLITE DADS</h2>
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
            <div>
                <a href="/pelanggan/export" class="btn-export">📊 Export Excel (CSV)</a>
                <button class="btn-add" onclick="openModal()">+ Input Pelanggan Baru</button>
            </div>
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
                <h3>Pending</h3>
                <div class="number" style="color: #d97706;">{{ $pelanggan->where('pengisi', 'IKR')->where('status_validasi', 'pending')->count() }}</div>
            </div>
            <div class="card">
                <h3>Valid</h3>
                <div class="number" style="color: #16a34a;">{{ $pelanggan->where('pengisi', 'IKR')->where('status_validasi', 'valid')->count() }}</div>
            </div>
            <div class="card">
                <h3>Tidak Valid</h3>
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
                        <th>Status Validasi</th>
                        <th>Catatan Revisi / Remark</th>
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
                                @if($item->status_validasi == 'pending')
                                    <span class="badge pending">Pending (Revisi)</span>
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
                            <small style="color: #64748b; font-style: italic;">
                                {{ $item->remark ?? '-' }}
                            </small>
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
                <input type="hidden" name="retry_count" value="{{ old('retry_count', 0) }}">

                <div class="form-grid">
                    
                    <div class="form-group full-width">
                        <label>1. Pilih Tipe Pengisi Data</label>
                        <select name="pengisi" id="pengisiSelect" onchange="toggleFormByPengisi()" required>
                            <option value="">-- Pilih Tipe Pengisi --</option>
                            <option value="IKR" {{ old('pengisi') == 'IKR' ? 'selected' : '' }}>IKR (Teknisi - Perlu Validasi)</option>
                            <option value="Sales" {{ old('pengisi') == 'Sales' ? 'selected' : '' }}>Sales (Langsung Tersimpan)</option>
                        </select>
                    </div>

                    <!-- FORM KHUSUS IKR (14 Data) -->
                    <div id="formIKR" class="form-section">
                        <h4 style="color:#0284c7; margin-bottom:15px; grid-column: span 2;">
                            Form Input Khusus IKR (Teknisi)
                            @if(old('retry_count', 0) >= 1)
                                <span style="color:#dc2626; font-size:12px; font-weight:bold;">[Percobaan Ke-2: Jika foto tetap tidak cocok, data akan masuk PENDING]</span>
                            @endif
                        </h4>
                        
                        <div class="form-group">
                            <label>2. Tanggal Aktivasi</label>
                            <input type="date" name="tanggal_aktivasi" class="ikr-input" value="{{ old('tanggal_aktivasi') }}">
                        </div>
                        <div class="form-group">
                            <label>3. Stasiun</label>
                            <select name="stasiun" class="ikr-input">
                                <option value="">-- Pilih Stasiun --</option>
                                <option value="Tasikmalaya" {{ old('stasiun') == 'Tasikmalaya' ? 'selected' : '' }}>Tasikmalaya</option>
                                <option value="Randuagung" {{ old('stasiun') == 'Randuagung' ? 'selected' : '' }}>Randuagung</option>
                                <option value="Garum" {{ old('stasiun') == 'Garum' ? 'selected' : '' }}>Garum</option>
                                <option value="Semarang Poncol" {{ old('stasiun') == 'Semarang Poncol' ? 'selected' : '' }}>Semarang Poncol</option>
                                <option value="Mojokerto" {{ old('stasiun') == 'Mojokerto' ? 'selected' : '' }}>Mojokerto</option>
                                <option value="Surabaya Gubeng" {{ old('stasiun') == 'Surabaya Gubeng' ? 'selected' : '' }}>Surabaya Gubeng</option>
                                <option value="Malang" {{ old('stasiun') == 'Malang' ? 'selected' : '' }}>Malang</option>
                                <option value="Talun" {{ old('stasiun') == 'Talun' ? 'selected' : '' }}>Talun</option>
                                <option value="Kediri" {{ old('stasiun') == 'Kediri' ? 'selected' : '' }}>Kediri</option>
                                <option value="Tulungagung" {{ old('stasiun') == 'Tulungagung' ? 'selected' : '' }}>Tulungagung</option>
                                <option value="Jombang" {{ old('stasiun') == 'Jombang' ? 'selected' : '' }}>Jombang</option>
                                <option value="Probolinggo" {{ old('stasiun') == 'Probolinggo' ? 'selected' : '' }}>Probolinggo</option>
                                <option value="Wanaraja" {{ old('stasiun') == 'Wanaraja' ? 'selected' : '' }}>Wanaraja</option>
                                <option value="Pasirjengkol" {{ old('stasiun') == 'Pasirjengkol' ? 'selected' : '' }}>Pasirjengkol</option>
                                <option value="Wlingi" {{ old('stasiun') == 'Wlingi' ? 'selected' : '' }}>Wlingi</option>
                                <option value="Kepanjen" {{ old('stasiun') == 'Kepanjen' ? 'selected' : '' }}>Kepanjen</option>
                                <option value="Kalioso" {{ old('stasiun') == 'Kalioso' ? 'selected' : '' }}>Kalioso</option>
                                <option value="Salem" {{ old('stasiun') == 'Salem' ? 'selected' : '' }}>Salem</option>
                                <option value="Sukoharjo" {{ old('stasiun') == 'Sukoharjo' ? 'selected' : '' }}>Sukoharjo</option>
                                <option value="Kertosono" {{ old('stasiun') == 'Kertosono' ? 'selected' : '' }}>Kertosono</option>
                                <option value="Jerakah" {{ old('stasiun') == 'Jerakah' ? 'selected' : '' }}>Jerakah</option>
                                <option value="Nganjuk" {{ old('stasiun') == 'Nganjuk' ? 'selected' : '' }}>Nganjuk</option>
                                <option value="Gedebage" {{ old('stasiun') == 'Gedebage' ? 'selected' : '' }}>Gedebage</option>
                                <option value="Tarik" {{ old('stasiun') == 'Tarik' ? 'selected' : '' }}>Tarik</option>
                                <option value="Sumbergempol" {{ old('stasiun') == 'Sumbergempol' ? 'selected' : '' }}>Sumbergempol</option>
                                <option value="Cicalengka" {{ old('stasiun') == 'Cicalengka' ? 'selected' : '' }}>Cicalengka</option>
                                <option value="Sidoarjo" {{ old('stasiun') == 'Sidoarjo' ? 'selected' : '' }}>Sidoarjo</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>4. CID (Customer ID)</label>
                            <input type="text" name="cid" placeholder="Masukkan CID" class="ikr-input" value="{{ old('cid') }}">
                        </div>
                        <div class="form-group">
                            <label>5. SN ONT</label>
                            <input type="text" name="sn_ont" placeholder="Masukkan SN ONT" class="ikr-input" value="{{ old('sn_ont') }}">
                        </div>
                        <div class="form-group">
                            <label>6. Nama Pelanggan</label>
                            <input type="text" name="nama" placeholder="Nama Pelanggan" class="ikr-input" value="{{ old('nama') }}">
                        </div>
                        <div class="form-group">
                            <label>7. No HP</label>
                            <input type="text" name="no_hp" placeholder="Contoh: 08123456789" class="ikr-input" value="{{ old('no_hp') }}">
                        </div>
                        <div class="form-group">
                            <label>8. Nama Teknisi (TIM)</label>
                            <input type="text" name="nama_teknisi" placeholder="Nama Teknisi/Tim" class="ikr-input" value="{{ old('nama_teknisi') }}">
                        </div>

                        <div class="form-group">
                            <label>9. Sumber WO</label>
                            <select name="sumber_wo" class="ikr-input">
                                <option value="">-- Pilih Sumber WO --</option>
                                <option value="Door to Door" {{ old('sumber_wo') == 'Door to Door' ? 'selected' : '' }}>Door to Door</option>
                                <option value="Sales" {{ old('sumber_wo') == 'Sales' ? 'selected' : '' }}>Sales</option>
                                <option value="Program" {{ old('sumber_wo') == 'Program' ? 'selected' : '' }}>Program</option>
                                <option value="Affiliate" {{ old('sumber_wo') == 'Affiliate' ? 'selected' : '' }}>Affiliate</option>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label>10. PIC Sales</label>
                            <input type="text" name="pic_sales" placeholder="Nama PIC Sales" class="ikr-input" value="{{ old('pic_sales') }}">
                        </div>
                        <div class="form-group">
                            <label>11. Foto KTP (Unggah Foto Jelas)</label>
                            <input type="file" name="foto_ktp" accept="image/*" class="ikr-input">
                        </div>
                        <div class="form-group">
                            <label>12. Foto BAST Pelanggan (Unggah Foto Jelas)</label>
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
                                <option value="Tasikmalaya">Tasikmalaya</option><option value="Randuagung">Randuagung</option><option value="Garum">Garum</option>
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

        @if($errors->has('error'))
            openModal();
        @endif
    </script>

</body>
</html>
