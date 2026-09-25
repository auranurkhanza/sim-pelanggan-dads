<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIVALID — Customer Management System</title>
    <!-- Import Font Premium Modern -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
        body { display: flex; height: 100vh; background-color: #090d16; color: #f1f5f9; overflow-x: hidden; }
        
        /* Navbar Mobile Header */
        .mobile-header { display: none; width: 100%; background: #0f172a; padding: 14px 20px; border-bottom: 1px solid #1e293b; align-items: center; justify-content: space-between; position: fixed; top: 0; left: 0; z-index: 101; }
        .mobile-header h2 { font-size: 15px; font-weight: 800; color: #38bdf8; letter-spacing: -0.3px; }
        .menu-toggle { background: rgba(56, 189, 248, 0.1); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.2); padding: 8px 14px; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; }

        /* Sidebar Navigation */
        .sidebar { width: 260px; background: #0f172a; color: white; padding: 24px 20px; display: flex; flex-direction: column; justify-content: space-between; border-right: 1px solid #1e293b; transition: transform 0.3s ease; z-index: 102; }
        .brand-box { padding-bottom: 20px; border-bottom: 1px solid #1e293b; margin-bottom: 24px; }
        .brand-title { font-size: 15px; font-weight: 800; color: #f8fafc; letter-spacing: 0.5px; }
        .brand-sub { font-size: 10px; font-weight: 700; color: #38bdf8; letter-spacing: 1px; text-transform: uppercase; margin-top: 2px; }
        
        .menu a { display: flex; align-items: center; gap: 10px; color: #94a3b8; text-decoration: none; padding: 10px 14px; border-radius: 8px; margin-bottom: 6px; font-size: 13.5px; font-weight: 600; transition: all 0.2s; }
        .menu a:hover { background: rgba(255, 255, 255, 0.04); color: #f8fafc; }
        .menu a.active { background: #0284c7; color: white; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3); }
        
        .logout-btn { background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); padding: 11px; border-radius: 8px; cursor: pointer; width: 100%; text-align: center; text-decoration: none; display: block; font-size: 13px; font-weight: 600; transition: all 0.2s; }
        .logout-btn:hover { background: #ef4444; color: white; }
        .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 100; }

        /* Main Content */
        .main-content { flex: 1; padding: 32px 36px; overflow-y: auto; background-color: #090d16; width: 100%; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 14px; }
        .header-title h1 { font-size: 22px; color: #f8fafc; font-weight: 700; letter-spacing: -0.4px; }
        .header-title p { font-size: 13px; color: #64748b; margin-top: 2px; }
        
        .btn-group { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn-add { background: #0284c7; color: white; border: none; padding: 10px 18px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25); transition: all 0.2s; }
        .btn-add:hover { background: #0369a1; transform: translateY(-1px); }
        .btn-export { background: #10b981; color: white; border: none; padding: 10px 18px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25); transition: all 0.2s; }
        .btn-export:hover { background: #059669; transform: translateY(-1px); }
        
        /* Stats Cards Minimalis */
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .card { background: #0f172a; padding: 18px 20px; border-radius: 12px; border: 1px solid #1e293b; transition: border-color 0.2s; }
        .card:hover { border-color: #334155; }
        .card h3 { font-size: 11.5px; color: #64748b; margin-bottom: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; }
        .card .number { font-size: 26px; font-weight: 800; color: #f8fafc; letter-spacing: -0.5px; }

        /* Modern Filter Bar */
        .filter-bar { background: #0f172a; padding: 14px 18px; border-radius: 12px; border: 1px solid #1e293b; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between; }
        .filter-group { display: flex; flex-wrap: wrap; gap: 10px; flex: 1; }
        .filter-input, .filter-select { padding: 8.5px 12px; background-color: #090d16; border: 1px solid #1e293b; border-radius: 8px; font-size: 13px; color: #f1f5f9; outline: none; transition: all 0.2s; }
        .filter-input { flex: 1.5; min-width: 180px; }
        .filter-select { flex: 1; min-width: 130px; }
        .filter-input:focus, .filter-select:focus { border-color: #0284c7; box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15); }
        .filter-actions { display: flex; gap: 8px; }
        .btn-filter { padding: 8.5px 16px; background-color: #1e293b; color: #f1f5f9; border: 1px solid #334155; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; transition: all 0.2s; }
        .btn-filter:hover { background-color: #334155; }
        .btn-reset { padding: 8.5px 16px; background-color: transparent; color: #64748b; border: 1px solid transparent; border-radius: 8px; font-weight: 600; font-size: 13px; text-decoration: none; transition: all 0.2s; }
        .btn-reset:hover { color: #f1f5f9; }
        
        /* Clean Table Style */
        .table-container { background: #0f172a; padding: 20px; border-radius: 12px; border: 1px solid #1e293b; overflow-x: auto; }
        .table-header h3 { font-size: 15px; color: #f8fafc; font-weight: 700; margin-bottom: 16px; letter-spacing: -0.2px; }
        
        table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 650px; }
        th, td { padding: 12px 14px; text-align: left; border-bottom: 1px solid #1e293b; }
        th { background-color: #090d16; color: #64748b; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
        td { color: #cbd5e1; }
        tr:last-child td { border-bottom: none; }
        
        .badge { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; display: inline-block; letter-spacing: 0.2px; }
        .badge.valid { background-color: rgba(16, 185, 129, 0.12); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.2); }
        .badge.pending { background-color: rgba(245, 158, 11, 0.12); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.2); }
        .badge.invalid { background-color: rgba(239, 68, 68, 0.12); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); }
        .badge.sales { background-color: rgba(56, 189, 248, 0.12); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.2); }
        
        .btn-action { padding: 5px 10px; border: none; border-radius: 6px; font-size: 11.5px; font-weight: 600; cursor: pointer; color: white; text-decoration: none; margin-right: 3px; transition: opacity 0.2s; }
        .btn-action:hover { opacity: 0.85; }
        .btn-valid { background-color: #059669; }
        .btn-invalid { background-color: #dc2626; }
        .btn-delete { background-color: #475569; }
        
        .alert-success { padding: 12px 16px; background-color: rgba(16, 185, 129, 0.1); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 10px; margin-bottom: 20px; font-size: 13px; font-weight: 500; }
        .alert-danger { padding: 12px 16px; background-color: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 10px; margin-bottom: 20px; font-size: 13px; font-weight: 500; }
        
        /* Modal Form Minimalis */
        .modal { display: none; position: fixed; z-index: 200; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.75); backdrop-filter: blur(6px); justify-content: center; align-items: center; padding: 16px; }
        .modal-content { background-color: #0f172a; padding: 24px; border-radius: 16px; border: 1px solid #1e293b; width: 100%; max-width: 640px; max-height: 90vh; overflow-y: auto; color: #f8fafc; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5); }
        .modal-content h3 { margin-bottom: 18px; color: #f8fafc; font-size: 17px; font-weight: 700; }
        .form-grid { display: grid; grid-template-columns: 1fr; gap: 14px; }
        @media (min-width: 600px) { .form-grid { grid-template-columns: 1fr 1fr; } }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-size: 12px; font-weight: 600; margin-bottom: 6px; color: #94a3b8; }
        .form-group input, .form-group select { padding: 9.5px 12px; background-color: #090d16; border: 1px solid #1e293b; border-radius: 8px; font-size: 13px; color: #f8fafc; outline: none; transition: border-color 0.2s; }
        .form-group input:focus, .form-group select:focus { border-color: #0284c7; }
        .full-width { grid-column: 1 / -1; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
        .btn-cancel { background-color: #1e293b; color: #94a3b8; border: 1px solid #334155; padding: 9px 16px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px; }
        .btn-cancel:hover { color: #f1f5f9; }
        .form-section { display: none; margin-top: 8px; padding-top: 14px; border-top: 1px dashed #1e293b; grid-column: 1 / -1; }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .mobile-header { display: flex; }
            .sidebar { position: fixed; top: 0; left: 0; height: 100vh; transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .overlay.active { display: block; }
            .main-content { padding: 75px 16px 24px 16px; }
            .header-title h1 { font-size: 19px; }
            .btn-group { width: 100%; }
            .btn-add, .btn-export { flex: 1; justify-content: center; font-size: 12.5px; padding: 10px; }
            .filter-input, .filter-select { min-width: 100%; }
            .filter-actions { width: 100%; }
        }
    </style>
</head>
<body>

    <div class="mobile-header">
        <h2>SIVALID STARLITE</h2>
        <button class="menu-toggle" onclick="toggleSidebar()">☰ Menu</button>
    </div>

    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <div class="sidebar" id="sidebar">
        <div>
            <div class="brand-box">
                <div class="brand-title">SIVALID SYSTEM</div>
                <div class="brand-sub">STARLITE x PT DADS</div>
            </div>
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
            <div class="header-title">
                <h1>Validasi Data Pelanggan</h1>
                <p>Kelola dan verifikasi data pengajuan IKR & Sales</p>
            </div>
            <div class="btn-group">
                <a href="/pelanggan/export" class="btn-export">📊 Export Excel</a>
                <button class="btn-add" onclick="openModal()">+ Input Baru</button>
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

        <form action="/dashboard" method="GET" class="filter-bar">
            <div class="filter-group">
                <input type="text" name="search" class="filter-input" placeholder="Cari Nama, CID, NIK..." value="{{ request('search') }}">
                
                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="valid" {{ request('status') == 'valid' ? 'selected' : '' }}>Valid</option>
                    <option value="invalid" {{ request('status') == 'invalid' ? 'selected' : '' }}>Tidak Valid</option>
                </select>

                <select name="stasiun" class="filter-select">
                    <option value="">Semua Stasiun</option>
                    <option value="Tasikmalaya" {{ request('stasiun') == 'Tasikmalaya' ? 'selected' : '' }}>Tasikmalaya</option>
                    <option value="Randuagung" {{ request('stasiun') == 'Randuagung' ? 'selected' : '' }}>Randuagung</option>
                    <option value="Garum" {{ request('stasiun') == 'Garum' ? 'selected' : '' }}>Garum</option>
                    <option value="Malang" {{ request('stasiun') == 'Malang' ? 'selected' : '' }}>Malang</option>
                    <option value="Semarang Poncol" {{ request('stasiun') == 'Semarang Poncol' ? 'selected' : '' }}>Semarang Poncol</option>
                </select>

                <select name="sort" class="filter-select">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Urutkan: Terlama</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama: A - Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama: Z - A</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">Filter</button>
                <a href="/dashboard" class="btn-reset">Reset</a>
            </div>
        </form>

        <div class="table-container">
            <div class="table-header">
                <h3>Antrean Validasi (Starlite FTTH)</h3>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>CID / Nama</th>
                        <th>Pengisi & Stasiun</th>
                        <th>Status Validasi</th>
                        <th>Catatan / Remark</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggan as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->cid ?? $item->nik ?? 'N/A' }}</strong><br>
                            <small style="color: #64748b;">{{ $item->nama }} ({{ $item->no_hp ?? '-' }})</small>
                        </td>
                        <td>
                            <span class="badge" style="background: #1e293b; color: #f8fafc;">{{ strtoupper($item->pengisi ?? '-') }}</span><br>
                            <small style="color: #64748b;">{{ $item->stasiun ?? '-' }}</small>
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
                            <small style="color: #64748b; font-style: italic;">{{ $item->remark ?? '-' }}</small>
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
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #64748b; padding: 24px;">
                            Belum ada data pelanggan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form Input -->
    <div class="modal" id="inputModal">
        <div class="modal-content">
            <h3>Input Data Pelanggan Baru</h3>
            
            <form action="/pelanggan/store" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="retry_count" value="{{ old('retry_count', 0) }}">

                <div class="form-grid">
                    
                    <div class="form-group full-width">
                        <label>1. Tipe Pengisi Data</label>
                        <select name="pengisi" id="pengisiSelect" onchange="toggleFormByPengisi()" required>
                            <option value="">-- Pilih Tipe Pengisi --</option>
                            <option value="IKR" {{ old('pengisi') == 'IKR' ? 'selected' : '' }}>IKR (Teknisi - Perlu Validasi)</option>
                            <option value="Sales" {{ old('pengisi') == 'Sales' ? 'selected' : '' }}>Sales (Langsung Tersimpan)</option>
                        </select>
                    </div>

                    <!-- FORM IKR -->
                    <div id="formIKR" class="form-section">
                        <h4 style="color:#0284c7; margin-bottom:12px; font-size: 13.5px;" class="full-width">Form Input Khusus Teknisi</h4>
                        
                        <div class="form-group"><label>2. Tanggal Aktivasi</label><input type="date" name="tanggal_aktivasi" class="ikr-input" value="{{ old('tanggal_aktivasi') }}"></div>
                        <div class="form-group"><label>3. Stasiun</label>
                            <select name="stasiun" class="ikr-input">
                                <option value="">-- Pilih Stasiun --</option>
                                <option value="Tasikmalaya">Tasikmalaya</option><option value="Randuagung">Randuagung</option><option value="Garum">Garum</option><option value="Malang">Malang</option>
                            </select>
                        </div>
                        <div class="form-group"><label>4. CID (Customer ID)</label><input type="text" name="cid" placeholder="Masukkan CID" class="ikr-input" value="{{ old('cid') }}"></div>
                        <div class="form-group"><label>5. SN ONT</label><input type="text" name="sn_ont" placeholder="Masukkan SN ONT" class="ikr-input" value="{{ old('sn_ont') }}"></div>
                        <div class="form-group"><label>6. Nama Pelanggan</label><input type="text" name="nama" placeholder="Nama Pelanggan" class="ikr-input" value="{{ old('nama') }}"></div>
                        <div class="form-group"><label>7. No HP</label><input type="text" name="no_hp" placeholder="Contoh: 08123456789" class="ikr-input" value="{{ old('no_hp') }}"></div>
                        <div class="form-group"><label>8. Nama Teknisi</label><input type="text" name="nama_teknisi" placeholder="Teknisi/Tim" class="ikr-input" value="{{ old('nama_teknisi') }}"></div>
                        <div class="form-group"><label>9. Sumber WO</label>
                            <select name="sumber_wo" class="ikr-input">
                                <option value="">-- Sumber WO --</option>
                                <option value="Door to Door">Door to Door</option><option value="Sales">Sales</option>
                            </select>
                        </div>
                        <div class="form-group full-width"><label>10. PIC Sales</label><input type="text" name="pic_sales" placeholder="Nama Sales" class="ikr-input" value="{{ old('pic_sales') }}"></div>
                        <div class="form-group"><label>11. Foto KTP</label><input type="file" name="foto_ktp" accept="image/*" class="ikr-input"></div>
                        <div class="form-group"><label>12. Foto BAST</label><input type="file" name="foto_bast" accept="image/*" class="ikr-input"></div>
                        <div class="form-group"><label>13. Foto Pelanggan</label><input type="file" name="foto_pelanggan" accept="image/*" class="ikr-input"></div>
                        <div class="form-group"><label>14. Foto Bukti Transfer</label><input type="file" name="foto_bukti_transfer" accept="image/*" class="ikr-input"></div>
                    </div>

                    <!-- FORM SALES -->
                    <div id="formSales" class="form-section">
                        <h4 style="color:#f59e0b; margin-bottom:12px; font-size: 13.5px;" class="full-width">Form Input Khusus Sales</h4>
                        <div class="form-group"><label>2. Tanggal Aktivasi</label><input type="date" name="tanggal_aktivasi" class="sales-input"></div>
                        <div class="form-group"><label>3. Nama Sales</label><input type="text" name="pic_sales" placeholder="Nama Sales" class="sales-input"></div>
                        <div class="form-group"><label>4. Stasiun</label>
                            <select name="stasiun" class="sales-input">
                                <option value="">-- Pilih Stasiun --</option>
                                <option value="Tasikmalaya">Tasikmalaya</option><option value="Randuagung">Randuagung</option><option value="Garum">Garum</option>
                            </select>
                        </div>
                        <div class="form-group"><label>5. Nama Pelanggan</label><input type="text" name="nama" placeholder="Nama Pelanggan" class="sales-input"></div>
                        <div class="form-group"><label>6. NIK Pelanggan</label><input type="text" name="nik" placeholder="16 Digit NIK" class="sales-input"></div>
                        <div class="form-group"><label>7. No HP</label><input type="text" name="no_hp" placeholder="No HP" class="sales-input"></div>
                        <div class="form-group"><label>8. Foto KTP</label><input type="file" name="foto_ktp" accept="image/*" class="sales-input"></div>
                        <div class="form-group"><label>9. Foto Bukti Transfer</label><input type="file" name="foto_bukti_transfer" accept="image/*" class="sales-input"></div>
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
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }

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
