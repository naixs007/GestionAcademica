<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión - {{ config('app.name') }}</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            display: flex;
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .login-right {
            flex: 1;
            padding: 60px 40px;
        }

        .logo-container {
            margin-bottom: 30px;
        }

        .logo-container i {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .welcome-text h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .welcome-text p {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .form-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .form-subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
        }

        .checkbox-group label {
            color: #666;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 18px;
            padding: 5px;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .features {
            margin-top: 40px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .feature-item i {
            font-size: 24px;
            margin-right: 15px;
        }

        .feature-item div h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .feature-item div p {
            font-size: 14px;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
            }

            .login-left {
                padding: 40px 30px;
            }

            .login-right {
                padding: 40px 30px;
            }

            .features {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Left Side - Branding -->
            <div class="login-left">
                <div class="logo-container">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>

                <div class="welcome-text">
                    <h1>Sistema de Gestión Académica</h1>
                    <p>Bienvenido al sistema integrado de gestión académica. Administra tu institución de manera eficiente y moderna.</p>
                </div>

                <div class="features">
                    <div class="feature-item">
                        <i class="bi bi-calendar-check"></i>
                        <div>
                            <h3>Control de Asistencia</h3>
                            <p>Registro y seguimiento en tiempo real</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-people-fill"></i>
                        <div>
                            <h3>Gestión de Docentes</h3>
                            <p>Administración completa del personal</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-graph-up"></i>
                        <div>
                            <h3>Reportes Avanzados</h3>
                            <p>Análisis y estadísticas detalladas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="login-right">
                <h2 class="form-title">Iniciar Sesión</h2>
                <p class="form-subtitle">Ingresa tus credenciales para acceder</p>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>
                            Correo Electrónico
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="form-input @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="ejemplo@correo.com"
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-1"></i>
                            Contraseña
                        </label>
                        <div class="password-wrapper">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-input @error('password') is-invalid @enderror"
                                required
                                autocomplete="current-password"
                                placeholder="Ingresa tu contraseña"
                                style="padding-right: 45px;"
                            >
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="checkbox-group">
                        <input id="remember_me" type="checkbox" name="remember">
                        <label for="remember_me">Mantener sesión iniciada</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Iniciar Sesión
                    </button>

                    <!-- Forgot Password -->
                    @if (Route::has('password.request'))
                        <div class="forgot-password">
                            <a href="{{ route('password.request') }}">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            togglePassword.addEventListener('click', function() {
                // Alternar el tipo de input
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Alternar el icono
                if (type === 'password') {
                    toggleIcon.classList.remove('bi-eye-slash');
                    toggleIcon.classList.add('bi-eye');
                } else {
                    toggleIcon.classList.remove('bi-eye');
                    toggleIcon.classList.add('bi-eye-slash');
                }
            });
        });
    </script>
</body>
</html>
