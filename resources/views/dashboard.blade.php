<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Data - SIVALID STARLITE DADS</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; height: 100vh; background-color: #0f172a; color: #f8fafc; }
        
        .sidebar { width: 260px; background: linear-gradient(180deg, #0b132b 0%, #1c2541 100%); color: white; padding: 22px; display: flex; flex-direction: column; justify-content: space-between; border-right: 1px solid #1e293b; }
        .sidebar h2 { font-size: 16px; font-weight: 800; margin-bottom: 25px; color: #38bdf8; border-bottom: 2px solid #0284c7; padding-bottom: 12px; letter-spacing: 1px; text-transform: uppercase; text-shadow: 0 0 10px rgba(56, 189, 248, 0.3); }
        .menu a { display: block; color: #94a3b8; text-decoration: none; padding: 12px 16px; border-radius: 8px; margin-bottom: 8px; font-size: 14px; font-weight: 600; transition: all 0.3s; }
        .menu a:hover, .menu a.active { background: linear-gradient(90deg, #0284c7 0%, #0369a1 100%); color: white; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.4); }
        .logout-btn { background-color: #ef4444; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; width: 100%; text-align: center; text-decoration: none; display: block; font-size: 14px; font-weight: 600; transition: background 0.2s; }
        .logout-btn:hover { background-color: #dc2626; }
        
        .main-content { flex: 1; padding: 28px; overflow-y: auto; background-color: #0f172a; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .header h1 { font-size: 24px; color: #f8fafc; font-weight: 700; letter-spacing: -0.5px; }
        .btn-add { background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: white; border: none; padding: 11px 18px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px; text-decoration: none; display: inline-block; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3); transition: transform 0.2s; }
        .btn-add:hover { transform: translateY(-2px); }
        .btn-export { background: linear-gradient(135deg, #10b981 0%, #047857 100%); color: white; border: none; padding: 11px 18px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px; text-decoration: none; margin-right: 10px; display: inline-block; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3); transition: transform 0.2s; }
        .btn-export:hover { transform: translateY(-2px); }
        
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 28px; }
        .card { background-color: #1e293b; padding: 20px; border-radius: 12px; border: 1px solid #334155; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2); }
        .card h3 { font-size: 13px; color: #94a3b8; margin-bottom: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .card .number { font-size: 26px; font-weight: 800; color: #f8fafc; }
        
        .table-container { background-color: #1e293b; padding: 22px; border-radius: 12px; border: 1px solid #334155; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3); }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px; }
        .table-header h3 { font-size: 16px; color: #38bdf8; font-weight: 700; }
        
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 14px; text-align: left; border-bottom: 1px solid #334155; }
        th { background-color: #0f172a; color: #cbd5e1; font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
        td { color: #e2e8f0; }
        
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block; }
        .badge.valid { background-color: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .badge.pending { background-color: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
        .badge.invalid { background-color: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .badge.sales { background-color: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
        
        .btn-action { padding: 7px 12px; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; color: white; text-decoration: none; margin-right: 4px; }
        .btn-valid { background-color: #10b981; }
        .btn-invalid { background-color: #ef4444; }
        .btn-delete { background-color: #64748b; }
        
        .alert-success { padding: 14px; background-color: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid #10b981; border-radius: 8px; margin-bottom: 22px; font-size: 14px; }
        .alert-danger { padding: 14px; background-color: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid #ef4444; border-radius: 8px; margin-bottom: 22px; font-size: 14px; }
        
        .modal { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.75); backdrop-filter: blur(4px); justify-content: center; align-items: center; }
        .modal-content { background-color: #1e293b; padding: 28px; border-radius: 12px; border: 1px solid #334155; width: 680px; max-height: 90vh; overflow-y: auto; color: #f8fafc; }
        .modal-content h3 { margin-bottom: 18px; color: #38bdf8; font-size: 18px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-size: 12px; font-weight: 600; margin-bottom: 6px; color: #cbd5e1; }
        .form-group input, .form-group select { padding: 10px 12px; background-color: #0f172a; border: 1px solid #334155; border-radius: 8px; font-size: 13px; color: #f8fafc; }
        .form-group input:focus, .form-group select:focus { border-color: #38bdf8; outline: none; }
        .full-width { grid-column: span 2; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; }
        .btn-cancel { background-color: #475569; color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        
        .form-section { display: none; margin-top: 10px; padding-top: 15px; border-top: 1px dashed #334155; grid-column: span 2; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div>
            <h2>SIVALID STARLITE DADS</h2>
            <div class="menu">
                <a href="/dashboard" class="active">Validasi Data</a>
                <a href="/pelanggan">Semua Pelanggan</a>
                <a href="/laporan">Laporan Validasi</a>
            </div>
        </div>
        <a href="/login" class="logout-btn">Keluar / Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Validasi Data Pelanggan</h1>
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
                <h3>Total Pengajuan</h3>
                <div class="number">{{ $pelanggan->where('pengisi', 'IKR')->count() }}</div>
            </div>
            <div class="card">
                <h3>Pending</h3>
                <div class="number" style="color: #fbbf24;">{{ $pelanggan->where('pengisi', 'IKR')->where('status_validasi', 'pending')->count() }}</div>
            </div>
            <div class="card">
                <h3>Valid</h3>
                <div class="number" style="color: #34d399;">{{ $pelanggan->where('pengisi', 'IKR')->where('status_validasi', 'valid')->count() }}</div>
            </div>
            <div class="card">
                <h3>Tidak Valid</h3>
                <div class="number" style="color: #f87171;">{{ $pelanggan->where('pengisi', 'IKR')->where('status_validasi', 'invalid')->count() }}</div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h3>Daftar Antrean Validasi (Starlite FTTH)</h3>
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
                            <small style="color: #94a3b8;">{{ $item->nama }} ({{ $item->no_hp ?? '-' }})</small>
                        </td>
                        <td>
                            <span class="badge" style="background-color: #334155; color: #f8fafc;">{{ strtoupper($item->pengisi ?? '-') }}</span><br>
                            <small style="color: #94a3b8;">{{ $item->stasiun ?? '-' }}</small>
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
                            <small style="color: #94a3b8; font-style: italic;">
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
            <h3>Input Data Pelanggan Starlite Baru</h3>
            
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
                        <h4 style="color:#38bdf8; margin-bottom:15px; grid-column: span 2;">
                            Form Input Khusus Teknisi
                            @if(old('retry_count', 0) >= 1)
                                <span style="color:#f87171; font-size:12px; font-weight:bold;">[Percobaan Ke-2: Jika foto tetap tidak cocok, data akan masuk PENDING]</span>
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
                        <h4 style="color:#fbbf24; margin-bottom:15px; grid-column: span 2;">Form Input Khusus Sales</h4>
                        
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
                formSales.style.display = 'none';
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
