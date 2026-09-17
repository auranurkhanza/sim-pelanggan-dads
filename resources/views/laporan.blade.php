<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Validasi - SIVALID STARLITE DADS</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; height: 100vh; background-color: #0f172a; color: #f8fafc; }
        
        .sidebar { width: 260px; background: linear-gradient(180deg, #0b132b 0%, #1c2541 100%); color: white; padding: 22px; display: flex; flex-direction: column; justify-content: space-between; border-right: 1px solid #1e293b; }
        .sidebar h2 { font-size: 16px; font-weight: 800; margin-bottom: 25px; color: #38bdf8; border-bottom: 2px solid #0284c7; padding-bottom: 12px; letter-spacing: 1px; text-transform: uppercase; text-shadow: 0 0 10px rgba(56, 189, 248, 0.3); }
        .menu a { display: block; color: #94a3b8; text-decoration: none; padding: 12px 16px; border-radius: 8px; margin-bottom: 8px; font-size: 14px; font-weight: 600; transition: all 0.3s; }
        .menu a:hover, .menu a.active { background: linear-gradient(90deg, #0284c7 0%, #0369a1 100%); color: white; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.4); }
        .logout-btn { background-color: #ef4444; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; width: 100%; text-align: center; text-decoration: none; display: block; font-size: 14px; font-weight: 600; }
        
        .main-content { flex: 1; padding: 28px; overflow-y: auto; background-color: #0f172a; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .header h1 { font-size: 24px; color: #f8fafc; font-weight: 700; }
        .btn-print { background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: white; border: none; padding: 11px 18px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px; }
        
        .card { background-color: #1e293b; padding: 22px; border-radius: 12px; border: 1px solid #334155; margin-bottom: 22px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2); }
        .card h3 { color: #38bdf8; font-size: 16px; margin-bottom: 8px; }
        
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 14px; text-align: left; border-bottom: 1px solid #334155; }
        th { background-color: #0f172a; color: #cbd5e1; font-weight: 700; text-transform: uppercase; font-size: 12px; }
        td { color: #e2e8f0; }
        
        .badge.valid { background-color: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <h2>SIVALID STARLITE DADS</h2>
            <div class="menu">
                <a href="/dashboard">Validasi Data</a>
                <a href="/pelanggan">Semua Pelanggan</a>
                <a href="/laporan" class="active">Laporan Validasi</a>
            </div>
        </div>
        <a href="/login" class="logout-btn">Keluar / Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Laporan Hasil Validasi</h1>
            <button onclick="window.print()" class="btn-print">🖨️ Cetak Laporan / PDF</button>
        </div>

        <div class="card">
            <h3>Rekapitulasi Selesai Validasi</h3>
            <p style="font-size: 14px; color: #94a3b8;">
                Total Data Divalidasi: <strong style="color: #f8fafc;">{{ $pelanggan->where('status_validasi', '!=', 'pending')->count() }}</strong> pelanggan
            </p>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Tgl Aktivasi</th>
                        <th>CID / Nama</th>
                        <th>Stasiun</th>
                        <th>Teknisi / Sales</th>
                        <th>Status Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pelanggan->where('status_validasi', '!=', 'pending') as $item)
                    <tr>
                        <td>{{ $item->tanggal_aktivasi ?? '-' }}</td>
                        <td><strong>{{ $item->cid ?? $item->nik ?? 'N/A' }}</strong><br><small style="color:#94a3b8;">{{ $item->nama }}</small></td>
                        <td>{{ $item->stasiun ?? '-' }}</td>
                        <td>{{ $item->nama_teknisi ?? '-' }} / {{ $item->pic_sales ?? '-' }}</td>
                        <td><span class="badge valid">{{ ucfirst($item->status_validasi) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
