<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Validasi - SIM Pelanggan DADS</title>
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
        .card { background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #f1f5f9; }
        th { background-color: #f8fafc; color: #475569; font-weight: 600; }
        .badge.valid { background-color: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <h2>SIM PELANGGAN DADS</h2>
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
        </div>

        <div class="card">
            <h3>Rekapitulasi Validasi</h3>
            <p style="margin-top: 10px; font-size: 14px; color: #64748b;">
                Total Data Divalidasi: <strong>{{ $pelanggan->where('status_validasi', '!=', 'pending')->count() }}</strong> pelanggan
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
                        <td><strong>{{ $item->cid ?? 'N/A' }}</strong><br><small>{{ $item->nama }}</small></td>
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
