<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Bienvenue - Salon de Beauté</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme-switcher.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <script>
        (function() {
            var theme = localStorage.getItem('salon-theme');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark-theme');
            }
        })();
    </script>
    
    <style>
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        }
        
        .welcome-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
        }
        
        .theme-toggle-floating {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 100;
        }
        
        .theme-toggle-floating:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }
        
        .theme-toggle-floating i {
            font-size: 22px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .theme-toggle-floating .fa-sun-o {
            display: block;
        }
        
        .theme-toggle-floating .fa-moon-o {
            display: none;
        }
        
        .dark-theme .theme-toggle-floating .fa-sun-o {
            display: none;
        }
        
        .dark-theme .theme-toggle-floating .fa-moon-o {
            display: block;
        }
        
        .welcome-card {
            background: white;
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.25);
            max-width: 480px;
            width: 90%;
        }
        
        .dark-theme .welcome-card {
            background: #1e293b;
            box-shadow: 0 25px 80px rgba(0,0,0,0.5);
        }
        
        .welcome-title {
            text-align: center;
            margin-bottom: 8px;
            color: #1e293b;
            font-size: 28px;
            font-weight: 700;
        }
        
        .dark-theme .welcome-title {
            color: #f1f5f9;
        }
        
        .welcome-subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 36px;
            font-size: 15px;
        }
        
        .dark-theme .welcome-subtitle {
            color: #94a3b8;
        }
        
        .login-option {
            display: flex;
            align-items: center;
            padding: 18px 24px;
            margin-bottom: 14px;
            border-radius: 16px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
        }
        
        .login-option:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
            text-decoration: none;
        }
        
        .login-option:active {
            transform: translateY(-2px);
        }
        
        .login-option .icon {
            font-size: 32px;
            margin-right: 18px;
            width: 40px;
            text-align: center;
        }
        
        .login-option .title {
            font-size: 17px;
            font-weight: 600;
            display: block;
            margin-bottom: 2px;
        }
        
        .login-option .desc {
            font-size: 13px;
            opacity: 0.85;
        }
        
        .login-client {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .login-employee {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
        }
        
        .login-admin {
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
            color: white;
        }
        
        .register-section {
            text-align: center;
            margin-top: 32px;
            padding-top: 28px;
            border-top: 1px solid #e2e8f0;
        }
        
        .dark-theme .register-section {
            border-top-color: #334155;
        }
        
        .register-section p {
            color: #64748b;
            margin-bottom: 14px;
            font-size: 14px;
        }
        
        .dark-theme .register-section p {
            color: #94a3b8;
        }
        
        .btn-register {
            display: inline-block;
            padding: 12px 28px;
            background: transparent;
            border: 2px solid #10b981;
            color: #10b981;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            background: #10b981;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        .dark-theme .btn-register {
            border-color: #34d399;
            color: #34d399;
        }
        
        .dark-theme .btn-register:hover {
            background: #34d399;
            color: #1e293b;
        }
        
        .public-services {
            text-align: center;
            margin-top: 20px;
        }
        
        .public-services a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        
        .public-services a:hover {
            color: #64748b;
        }
        
        .dark-theme .public-services a {
            color: #64748b;
        }
        
        .dark-theme .public-services a:hover {
            color: #94a3b8;
        }
        
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-left: 4px solid #10b981;
            color: #059669;
        }
        
        .dark-theme .alert-success {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }
        
        .alert-danger {
            background: rgba(244, 63, 94, 0.1);
            border-left: 4px solid #f43f5e;
            color: #e11d48;
        }
        
        .dark-theme .alert-danger {
            background: rgba(244, 63, 94, 0.15);
            color: #fb7185;
        }
        
        /* Dark theme for gradient background */
        .dark-theme .welcome-container {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        }
    </style>
</head>

<body>
    <div class="welcome-container">
        <button type="button" class="theme-toggle-floating" id="theme-toggle-welcome" title="Changer le thème">
            <i class="fa fa-sun-o"></i>
            <i class="fa fa-moon-o"></i>
        </button>
        
        <div class="welcome-card">
            <h1 class="welcome-title">💇 Salon de Beauté</h1>
            <p class="welcome-subtitle">Bienvenue ! Choisissez votre espace de connexion</p>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <a href="{{ route('client.login') }}" class="login-option login-client">
                <span class="icon">👤</span>
                <div>
                    <span class="title">Espace Client</span>
                    <span class="desc">Réservez vos rendez-vous et gérez votre compte</span>
                </div>
            </a>

            <a href="{{ route('employee.login') }}" class="login-option login-employee">
                <span class="icon">💼</span>
                <div>
                    <span class="title">Espace Employé</span>
                    <span class="desc">Gérez vos rendez-vous et planning</span>
                </div>
            </a>

            <a href="{{ route('login') }}" class="login-option login-admin">
                <span class="icon">🔐</span>
                <div>
                    <span class="title">Administration</span>
                    <span class="desc">Gérez le salon (réservé aux administrateurs)</span>
                </div>
            </a>

            <div class="register-section">
                <p>Vous êtes nouveau client ?</p>
                <a href="{{ route('client.register') }}" class="btn-register">
                    Créer un compte
                </a>
            </div>

            <div class="public-services">
                <a href="{{ route('services.public') }}">
                    📋 Découvrir nos services
                </a>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var toggleBtn = document.getElementById('theme-toggle-welcome');
    var THEME_KEY = 'salon-theme';
    
    // Apply saved theme
    var savedTheme = localStorage.getItem(THEME_KEY);
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }
    
    toggleBtn.addEventListener('click', function() {
        var isDark = document.body.classList.contains('dark-theme');
        
        if (isDark) {
            document.body.classList.remove('dark-theme');
            document.documentElement.classList.remove('dark-theme');
            localStorage.setItem(THEME_KEY, 'light');
        } else {
            document.body.classList.add('dark-theme');
            document.documentElement.classList.add('dark-theme');
            localStorage.setItem(THEME_KEY, 'dark');
        }
    });
});
</script>

</body>
</html>
