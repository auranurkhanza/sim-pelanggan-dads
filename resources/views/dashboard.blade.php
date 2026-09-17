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
        
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px; }
        .card { background: white; padding: 18px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .card h3 { font-size: 13px; color: #64748b; margin-bottom: 8px; }
        .card .number { font-size: 22px; font-weight: bold; color: #0f172a; }
        
        .table-container { background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
        .search-box { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; width: 280px; font-size: 14px; }
        
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #f1f5f9; }
        th { background-color: #f8fafc; color: #475569; font-weight: 600; }
        
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .badge.valid { background-color: #dcfce7; color: #166534; }
        .badge.pending { background-color: #fef9c3; color: #854d0e; }
        .badge.invalid { background-color: #fee2e2; color: #991b1b; }
        
        .btn-action { padding: 6px 12px; border: none; border-radius: 4px; font-size: 12px; cursor: pointer; color: white; text-decoration: none; }
        .btn-valid { background-color: #16a34a; }
        .btn-invalid { background-color: #dc2626; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div>
            <h2>SIM PELANGGAN DADS</h2>
            <div class="menu">
                <a href="/dashboard" class="active">Validasi Data</a>
                <a href="#">Semua Pelanggan</a>
                <a href="#">Laporan Validasi</a>
            </div>
        </div>
        <a href="/login" class="logout-btn">Keluar / Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Validasi Data Pelanggan</h1>
            <span>Petugas: <strong>Admin DADS</strong></span>
        </div>

        <div class="cards">
            <div class="card">
                <h3>Total Pengajuan</h3>
                <div class="number">120</div>
            </div>
            <div class="card">
                <h3>Belum Divalidasi</h3>
                <div class="number" style="color: #d97706;">15</div>
            </div>
            <div class="card">
                <h3>Data Valid</h3>
                <div class="number" style="color: #16a34a;">98</div>
            </div>
            <div class="card">
                <h3>Data Tidak Valid</h3>
                <div class="number" style="color: #dc2626;">7</div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h3>Daftar Antrean Validasi</h3>
                <input type="text" class="search-box" placeholder="Cari NIK / Nama / ID Pelanggan...">
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>ID / NIK</th>
                        <th>Nama Pelanggan</th>
                        <th>Alamat</th>
                        <th>Status Validasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>#PLG-001</strong><br><small>3275012304950001</small></td>
                        <td>Aura Nur Khanza</td>
                        <td>Jl. Jatiwaringin No. 45</td>
                        <td><span class="badge pending">Pending</span></td>
                        <td>
                            <button class="btn-action btn-valid">Setujui</button>
                            <button class="btn-action btn-invalid">Tolak</button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>#PLG-002</strong><br><small>3275081203880004</small></td>
                        <td>PT Pegadaian</td>
                        <td>Jl. Dago No. 102, Bandung</td>
                        <td><span class="badge valid">Valid</span></td>
                        <td>
                            <span style="color: #64748b; font-size: 12px;">Terverifikasi</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
