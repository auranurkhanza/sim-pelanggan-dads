<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIVALID STARLITE DADS</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        body { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            background: radial-gradient(circle at center, #1c2541 0%, #0f172a 100%); 
            color: #f8fafc; 
        }
        
        .login-card { 
            background-color: #1e293b; 
            padding: 40px 32px; 
            border-radius: 16px; 
            border: 1px solid #334155; 
            width: 400px; 
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.5), 0 0 20px rgba(56, 189, 248, 0.1); 
            text-align: center; 
        }

        /* Branding Header Starlite x PT DADS */
        .brand-header {
            margin-bottom: 24px;
        }

        .brand-logo-text {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: 2px;
            background: linear-gradient(90deg, #38bdf8 0%, #0284c7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: uppercase;
        }

        .brand-sub {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 1.5px;
            margin-top: 4px;
            text-transform: uppercase;
        }

        .brand-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #0284c7 50%, transparent 100%);
            margin: 16px 0 20px 0;
            border: none;
        }

        .login-card h3 { 
            font-size: 18px; 
            font-weight: 700; 
            color: #f8fafc; 
            margin-bottom: 4px; 
        }

        .login-card p { 
            font-size: 13px; 
            color: #94a3b8; 
            margin-bottom: 24px; 
        }
        
        .form-group { 
            text-align: left; 
            margin-bottom: 18px; 
        }

        .form-group label { 
            font-size: 12px; 
            font-weight: 600; 
            color: #cbd5e1; 
            display: block; 
            margin-bottom: 6px; 
        }

        .form-group input { 
            width: 100%; 
            padding: 12px 14px; 
            background-color: #0f172a; 
            border: 1px solid #334155; 
            border-radius: 8px; 
            color: #f8fafc; 
            font-size: 14px; 
            outline: none; 
            transition: all 0.2s; 
        }

        .form-group input:focus { 
            border-color: #38bdf8; 
            box-shadow: 0 0 8px rgba(56, 189, 248, 0.3); 
        }
        
        .btn-login { 
            width: 100%; 
            padding: 12px; 
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); 
            border: none; 
            border-radius: 8px; 
            color: white; 
            font-weight: 700; 
            font-size: 14px; 
            cursor: pointer; 
            margin-top: 8px; 
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.4); 
            transition: transform 0.2s, box-shadow 0.2s; 
        }

        .btn-login:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 6px 18px rgba(2, 132, 199, 0.5); 
        }
        
        .error-msg { 
            background-color: rgba(239, 68, 68, 0.2); 
            color: #f87171; 
            border: 1px solid #ef4444; 
            padding: 10px 12px; 
            border-radius: 8px; 
            font-size: 13px; 
            margin-bottom: 18px; 
            text-align: left; 
        }

        .footer-text {
            margin-top: 24px;
            font-size: 11px;
            color: #64748b;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-header">
            <div class="brand-logo-text">STARLITE</div>
            <div class="brand-sub">PT DUTA ANUGRAH DAMAI SEJAHTERA (DADS)</div>
        </div>

        <hr class="brand-divider">

        <h3>SIVALID SYSTEM</h3>
        <p>Sistem Informasi Validasi Data Pelanggan</p>

        @if($errors->any())
            <div class="error-msg">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Masukkan email..." required value="{{ old('email') }}">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password..." required>
            </div>

            <button type="submit" class="btn-login">Masuk ke Sistem</button>
        </form>

        <div class="footer-text">
            &copy; {{ date('Y') }} STARLITE x PT DADS. All Rights Reserved.
        </div>
    </div>

</body>
</html>
