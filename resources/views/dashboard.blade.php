<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIM Pelanggan DADS</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; height: 100vh; background-color: #f4f6f9; color: #333; }
        
        /* Sidebar */
        .sidebar { width: 250px; background-color: #2c3e50; color: white; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; }
        .sidebar h2 { font-size: 20px; margin-bottom: 30px; border-bottom: 1px solid #34495e; padding-bottom: 10px; }
        .menu a { display: block; color: #ecf0f1; text-decoration: none; padding: 12px; border-radius: 6px; margin-bottom: 8px; transition: 0.2s; }
        .menu a:hover, .menu a.active { background-color: #3498db; }
        .logout-btn { background-color: #e74c3c; color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer; width: 100%; text-align: center; text-decoration: none; display: block; }
        
        /* Main Content */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        /* Stats Cards */
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .card h3 { font-size: 14px; color: #7f8c8d; margin-bottom: 10px; }
        .card .number { font-size: 24px; font-weight: bold; color: #2c3e50; }
        
        /* Table */
        .table-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .table-container h3 { margin-bottom: 15px; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #555; }
        .status { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .status.active { background-color: #d4edda; color: #155724; }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div>
            <h2>SIM DADS</h2>
            <div class="menu">
                <a href="/dashboard" class="active">Dashboard</a>
                <a href="#">Data Pelanggan</a>
                <a href="#">Transaksi</a>
                <a href="#">Laporan</a>
                <a href="#">Pengaturan</a>
            </div>
        </div>
        <a href="/login" class="logout-btn">Keluar / Logout</a>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <div class="header">
            <h1>Dashboard Utama</h1>
            <span>Halo, <strong>Admin DADS</strong></span>
        </div>

        <!-- Statistics Cards -->
        <div class="cards">
            <div class="card">
                <h3>Total Pelanggan</h3>
                <div class="number">128</div>
            </div>
            <div class="card">
                <h3>Pelanggan Aktif</h3>
                <div class="number">115</div>
            </div>
            <div class="card">
                <h3>Transaksi Bulan Ini</h3>
                <div class="number">42</div>
            </div>
            <div class="card">
                <h3>Pendapatan</h3>
                <div class="number">Rp 15.400.000</div>
            </div>
        </div>

        <!-- Recent Data Table -->
        <div class="table-container">
            <h3>Pelanggan Terbaru</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pelanggan</th>
                        <th>Layanan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#PLG-001</td>
                        <td>Aura Nur Khanza</td>
                        <td>Paket FTTH Premium</td>
                        <td><span class="status active">Aktif</span></td>
                    </tr>
                    <tr>
                        <td>#PLG-002</td>
                        <td>PT Pegadaian</td>
                        <td>Dedicated Corporate</td>
                        <td><span class="status active">Aktif</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
