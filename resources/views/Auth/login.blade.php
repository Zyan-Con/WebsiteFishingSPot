<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SpotMancing</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .background {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(20, 30, 48, 0.9), rgba(36, 59, 85, 0.8)), 
                        url('https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=1600') center/cover;
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .left-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            z-index: 1;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 30px 28px;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 360px;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            text-align: center;
            margin-bottom: 22px;
        }

        .logo h1 {
            color: #1e40af;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .logo p {
            color: #64748b;
            font-size: 12px;
        }

        .form-group {
            margin-bottom: 14px;
            position: relative;
        }

        label {
            display: block;
            color: #334155;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: translateY(-1px);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            font-size: 12px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .remember:hover {
            color: #3b82f6;
        }

        .remember input[type="checkbox"] {
            width: auto;
            cursor: pointer;
            transform: scale(1.1);
        }

        .forgot-link {
            color: #3b82f6;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 12px;
        }

        .forgot-link:hover {
            color: #1e40af;
            transform: translateX(2px);
        }

        .btn {
            width: 100%;
            padding: 11px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            margin-bottom: 10px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .divider {
            text-align: center;
            margin: 14px 0;
            position: relative;
            color: #94a3b8;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: #e2e8f0;
        }

        .divider::before { left: 0; }
        .divider::after { right: 0; }

        .btn-google {
            background: white;
            color: #334155;
            border: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 13px;
        }

        .btn-google:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .btn-google:active {
            transform: translateY(0);
        }

        .google-icon {
            width: 18px;
            height: 18px;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .btn-google:hover .google-icon {
            animation: bounce 0.6s ease;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }

        .register-link {
            text-align: center;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 13px;
            animation: fadeInUp 0.8s ease-out 0.3s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            position: relative;
        }

        .register-link a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background: #3b82f6;
            transition: width 0.3s ease;
        }

        .register-link a:hover::after {
            width: 100%;
        }

        .register-link a:hover {
            color: #1e40af;
        }

        .alert {
            padding: 9px 12px;
            border-radius: 8px;
            margin-bottom: 14px;
            font-size: 12px;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        @media (max-width: 768px) {
            .login-box {
                padding: 28px 24px;
                max-width: 340px;
            }
            
            .logo h1 {
                font-size: 24px;
            }

            .logo p {
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="background"></div>
        
        <div class="left-section">
            <div class="login-box">
                <div class="logo">
                    <h1>🎣 SpotMancing</h1>
                    <p>Temukan spot mancing terbaik di Indonesia</p>
                </div>

                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Masukkan password">
                    </div>

                    <div class="remember-forgot">
                        <label class="remember">
                            <input type="checkbox" name="remember">
                            <span>Ingat Saya</span>
                        </label>
                        <a href="#" class="forgot-link">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary">Masuk</button>
                </form>

                <div class="divider">atau</div>

                <a href="{{ route('google.redirect') }}" class="btn btn-google" style="text-decoration: none;">
                    <svg class="google-icon" viewBox="0 0 24 24">
                    <svg class="google-icon" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Masuk dengan Google
                </a>

                <div class="register-link">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
