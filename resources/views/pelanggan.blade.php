<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Pelanggan - SIVALID STARLITE DADS</title>
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

        .filter-bar { background-color: #1e293b; padding: 16px 20px; border-radius: 12px; border: 1px solid #334155; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.2); }
        .filter-group { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
        .filter-input, .filter-select { padding: 9px 14px; background-color: #0f172a; border: 1px solid #334155; border-radius: 8px; font-size: 13px; color: #f8fafc; outline: none; }
        .filter-input:focus, .filter-select:focus { border-color: #38bdf8; box-shadow: 0 0 6px rgba(56, 189, 248, 0.3); }
        .btn-filter { padding: 9px 16px; background-color: #0284c7; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; }
        .btn-reset { padding: 9px 16px; background-color: #475569; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; text-decoration: none; }
        
        .table-container { background-color: #1e293b; padding: 22px; border-radius: 12px; border: 1px solid #334155; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3); }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 14px; text-align: left; border-bottom: 1px solid #334155; }
        th { background-color: #0f172a; color: #cbd5e1; font-weight: 700; text-transform: uppercase; font-size: 12px; }
        td { color: #e2e8f0; }
        
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block; }
        .badge.valid { background-color: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .badge.pending { background-color: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
        .badge.invalid { background-color: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .badge.sales { background-color: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <h2>SIVALID STARLITE DADS</h2>
            <div class="menu">
                <a href="/dashboard">Validasi Data</a>
                <a href="/pelanggan" class="active">Semua Pelanggan</a>
                <a href="/laporan">Laporan Validasi</a>
            </div>
        </div>
        <a href="/login" class="logout-btn">Keluar / Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Daftar Semua Pelanggan</h1>
        </div>

        <form action="/pelanggan" method="GET" class="filter-bar">
            <div class="filter-group">
                <input type="text" name="search" class="filter-input" placeholder="Cari Nama / CID / NIK..." value="{{ request('search') }}">
                
                <select name="status" class="filter-select">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="valid" {{ request('status') == 'valid' ? 'selected' : '' }}>Valid</option>
                    <option value="invalid" {{ request('status') == 'invalid' ? 'selected' : '' }}>Tidak Valid</option>
                </select>

                <select name="stasiun" class="filter-select">
                    <option value="">-- Semua Stasiun --</option>
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

            <div>
                <button type="submit" class="btn-filter">Terapkan Filter</button>
                <a href="/pelanggan" class="btn-reset">Reset</a>
            </div>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>CID / NIK / Nama</th>
                        <th>Pengisi</th>
                        <th>Stasiun</th>
                        <th>SN ONT</th>
                        <th>No HP</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggan as $item)
                    <tr>
                        <td><strong>{{ $item->cid ?? $item->nik ?? 'N/A' }}</strong><br><small style="color:#94a3b8;">{{ $item->nama }}</small></td>
                        <td><span class="badge" style="background-color: #334155; color: #f8fafc;">{{ strtoupper($item->pengisi ?? '-') }}</span></td>
                        <td>{{ $item->stasiun ?? '-' }}</td>
                        <td>{{ $item->sn_ont ?? '-' }}</td>
                        <td>{{ $item->no_hp ?? '-' }}</td>
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 24px;">
                            Belum ada data pelanggan yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
