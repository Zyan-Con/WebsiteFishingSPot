<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SpotMancing</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
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
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(30, 58, 138, 0.85)), 
                        url('https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1600') center/cover;
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

        .register-box {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 28px 26px;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 360px;
            animation: slideUp 0.8s ease-out;
            max-height: 95vh;
            overflow-y: auto;
        }

        .register-box::-webkit-scrollbar {
            width: 6px;
        }

        .register-box::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .register-box::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .register-box::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
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
            margin-bottom: 20px;
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
            margin-bottom: 12px;
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
            padding: 9px 14px;
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

        input.error {
            border-color: #ef4444;
        }

        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .error-message.show {
            display: block;
            animation: shake 0.3s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .btn {
            width: 100%;
            padding: 10px;
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
            margin-top: 6px;
            margin-bottom: 10px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .divider {
            text-align: center;
            margin: 12px 0;
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

        .login-link {
            text-align: center;
            margin-top: 14px;
            padding-top: 14px;
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

        .login-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            position: relative;
        }

        .login-link a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background: #3b82f6;
            transition: width 0.3s ease;
        }

        .login-link a:hover::after {
            width: 100%;
        }

        .login-link a:hover {
            color: #1e40af;
        }

        .alert {
            padding: 9px 12px;
            border-radius: 8px;
            margin-bottom: 12px;
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

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .password-strength {
            margin-top: 5px;
            height: 3px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
            display: none;
            animation: slideRight 0.3s ease-out;
        }

        @keyframes slideRight {
            from {
                transform: scaleX(0);
                transform-origin: left;
            }
            to {
                transform: scaleX(1);
            }
        }

        .password-strength.show {
            display: block;
        }

        .password-strength-bar {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { 
            width: 33%; 
            background: #ef4444; 
        }

        .strength-medium { 
            width: 66%; 
            background: #f59e0b; 
        }

        .strength-strong { 
            width: 100%; 
            background: #10b981; 
        }

        @media (max-width: 768px) {
            .register-box {
                padding: 26px 22px;
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
            <div class="register-box">
                <div class="logo">
                    <h1>🎣 SpotMancing</h1>
                    <p>Bergabung dengan komunitas pemancing Indonesia</p>
                </div>

                @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Masukkan nama lengkap">
                        <div class="error-message" id="name-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com">
                        <div class="error-message" id="email-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter">
                        <div class="password-strength" id="password-strength">
                            <div class="password-strength-bar" id="strength-bar"></div>
                        </div>
                        <div class="error-message" id="password-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Masukkan password lagi">
                        <div class="error-message" id="confirm-error"></div>
                    </div>

                    <button type="submit" class="btn btn-primary">Daftar Sekarang</button>
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
                    Daftar dengan Google
                </a>

                <div class="login-link">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('registerForm');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const strengthDiv = document.getElementById('password-strength');
        const strengthBar = document.getElementById('strength-bar');

        // Password strength indicator
        password.addEventListener('input', function() {
            const val = this.value;
            const strength = calculatePasswordStrength(val);
            
            if (val.length > 0) {
                strengthDiv.classList.add('show');
                strengthBar.className = 'password-strength-bar';
                
                if (strength < 40) {
                    strengthBar.classList.add('strength-weak');
                } else if (strength < 70) {
                    strengthBar.classList.add('strength-medium');
                } else {
                    strengthBar.classList.add('strength-strong');
                }
            } else {
                strengthDiv.classList.remove('show');
            }
        });

        function calculatePasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength += 25;
            if (password.length >= 12) strength += 15;
            if (/[a-z]/.test(password)) strength += 15;
            if (/[A-Z]/.test(password)) strength += 15;
            if (/[0-9]/.test(password)) strength += 15;
            if (/[^a-zA-Z0-9]/.test(password)) strength += 15;
            return strength;
        }

        // Form validation
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Reset errors
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('show');
            });
            document.querySelectorAll('input').forEach(el => {
                el.classList.remove('error');
            });

            // Validate password match
            if (password.value !== confirmPassword.value) {
                showError('confirm-error', 'Password tidak cocok');
                confirmPassword.classList.add('error');
                isValid = false;
            }

            // Validate password length
            if (password.value.length < 8) {
                showError('password-error', 'Password minimal 8 karakter');
                password.classList.add('error');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        function showError(id, message) {
            const errorEl = document.getElementById(id);
            errorEl.textContent = message;
            errorEl.classList.add('show');
        }
    </script>
</body>
</html>