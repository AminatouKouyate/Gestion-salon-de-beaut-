{{--
    Vue : Layout d'authentification
    Description : Template de base pour toutes les pages d'authentification : structure en deux colonnes (panneau décoratif gauche et formulaire droit), styles communs et scripts partagés.
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'KAARJA Beauté')</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script>
        (function(){var t=localStorage.getItem('salon-theme');if(t==='dark')document.documentElement.classList.add('dark-theme');})();
    </script>

    <style>
        :root {
            --primary: #B76E79; --primary-light: #D4979F; --primary-soft: #F8E8EE;
            --accent: #D4AF37; --bg: #FFF8F0; --dark: #4A1942; --dark-light: #6B2D5B;
            --rose-gold: var(--primary); --rose-gold-light: var(--primary-light);
            --soft-pink: var(--primary-soft); --warm-gold: var(--accent);
            --cream: var(--bg); --deep-plum: var(--dark); --plum-light: var(--dark-light);
            --white: #fff;
            --text-dark: #2D2D2D;
            --text-muted: #8E8E8E;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; color: var(--text-dark); overflow-x: hidden; min-height: 100vh; }
        h1,h2,h3,h4,h5 { font-family: 'Playfair Display', serif; }

        /* ===== SPLIT SCREEN LAYOUT ===== */
        .auth-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* --- LEFT PANEL --- */
        .auth-left {
            width: 40%;
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark-light) 40%, var(--primary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 60px 40px;
        }
        .auth-left::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -120px;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(212,175,55,0.1);
            pointer-events: none;
        }
        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(183,110,121,0.15);
            pointer-events: none;
        }

        /* --- RIGHT PANEL --- */
        .auth-right {
            width: 60%;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
        }
        .auth-right-inner {
            width: 100%;
            max-width: 440px;
        }

        /* ===== LEFT PANEL CONTENT ===== */
        .left-content {
            position: relative;
            z-index: 2;
            color: white;
            text-align: center;
        }
        .left-content h2 {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 12px;
        }
        .left-content h2 span {
            color: var(--accent);
        }
        .gold-line {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), var(--primary-light));
            margin: 0 auto 20px;
            border-radius: 3px;
        }
        .left-subtitle {
            font-size: 14px;
            opacity: 0.85;
            line-height: 1.7;
            margin-bottom: 30px;
        }
        .left-features {
            list-style: none;
            padding: 0;
            text-align: left;
            max-width: 280px;
            margin: 0 auto;
        }
        .left-features li {
            padding: 10px 0;
            font-size: 14px;
            font-weight: 500;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .left-features li i {
            width: 20px;
            text-align: center;
            color: var(--accent);
            font-size: 16px;
        }
        .left-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 32px;
            color: var(--accent);
            border: 2px solid rgba(212,175,55,0.3);
        }

        /* ===== FORM STYLES ===== */
        .form-logo {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--dark));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
            color: white;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .auth-right h3 {
            text-align: center;
            font-size: 26px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 6px;
        }

        .form-tagline {
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 28px;
        }

        /* Alerts */
        .alert-luxury {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .alert-luxury i {
            font-size: 16px;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .alert-luxury ul {
            margin: 0;
            padding-left: 16px;
        }
        .alert-danger {
            background: #fff0f0;
            border-left: 4px solid #e74c3c;
            color: #c0392b;
        }
        .alert-success {
            background: #f0faf0;
            border-left: 4px solid #27ae60;
            color: #1e8449;
        }

        /* Form groups */
        .form-group-luxury {
            margin-bottom: 20px;
        }
        .form-group-luxury label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
        }
        .form-group-luxury label i {
            color: var(--primary);
            margin-right: 4px;
            width: 16px;
            text-align: center;
        }

        /* Inputs */
        .input-luxury {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e8dfe2;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            background: white;
            transition: all 0.3s ease;
            outline: none;
        }
        .input-luxury:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(183,110,121,0.1);
        }
        .input-luxury::placeholder {
            color: #c0b8bb;
        }
        .input-luxury.is-invalid {
            border-color: #e74c3c;
        }

        /* Password wrapper */
        .password-wrapper {
            position: relative;
        }
        .password-wrapper .input-luxury {
            padding-right: 50px;
        }
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
            transition: color 0.3s;
        }
        .password-toggle:hover {
            color: var(--primary);
        }

        /* Form extras (remember + forgot) */
        .form-extras {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        /* Custom checkbox */
        .custom-check-luxury {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-dark);
            cursor: pointer;
            margin: 0;
        }
        .custom-check-luxury input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            border: 2px solid #e8dfe2;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            transition: all 0.3s;
            flex-shrink: 0;
        }
        .custom-check-luxury input[type="checkbox"]:checked {
            background: var(--primary);
            border-color: var(--primary);
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
            background-size: 12px;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Forgot link */
        .forgot-link {
            font-size: 13px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        .forgot-link:hover {
            color: var(--dark);
            text-decoration: none;
        }

        /* Primary button */
        .btn-luxury {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary), var(--dark));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .btn-luxury:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        }
        .btn-luxury:active {
            transform: translateY(0);
        }

        /* Divider */
        .divider-luxury {
            text-align: center;
            margin: 24px 0;
            position: relative;
        }
        .divider-luxury::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e8dfe2;
        }
        .divider-luxury span {
            position: relative;
            background: var(--bg);
            padding: 0 16px;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
        }

        /* Register button */
        .btn-register-luxury {
            display: block;
            width: 100%;
            padding: 14px;
            text-align: center;
            border: 2px solid var(--primary-light);
            border-radius: 12px;
            color: var(--primary);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            background: transparent;
        }
        .btn-register-luxury:hover {
            background: var(--primary-soft);
            border-color: var(--primary);
            color: var(--primary);
            text-decoration: none;
            transform: translateY(-2px);
        }

        /* Back link */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: var(--text-muted);
            font-size: 13px;
            text-decoration: none;
            transition: color 0.3s;
        }
        .back-link:hover {
            color: var(--primary);
            text-decoration: none;
        }
        .back-link i {
            margin-right: 4px;
        }

        /* ===== DARK THEME ===== */
        .dark-theme .auth-right {
            background: #1a1a2e;
        }
        .dark-theme .auth-right h3 {
            color: #E8E8E8;
        }
        .dark-theme .form-tagline {
            color: #999;
        }
        .dark-theme .form-group-luxury label {
            color: #ccc;
        }
        .dark-theme .input-luxury {
            background: #252540;
            border-color: #333355;
            color: #E8E8E8;
        }
        .dark-theme .input-luxury:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(183,110,121,0.15);
        }
        .dark-theme .input-luxury::placeholder {
            color: #666;
        }
        .dark-theme .custom-check-luxury {
            color: #ccc;
        }
        .dark-theme .custom-check-luxury input[type="checkbox"] {
            border-color: #444;
            background: #252540;
        }
        .dark-theme .divider-luxury span {
            background: #1a1a2e;
        }
        .dark-theme .divider-luxury::before {
            background: #333;
        }
        .dark-theme .btn-register-luxury {
            border-color: #444;
            color: var(--primary-light);
        }
        .dark-theme .btn-register-luxury:hover {
            background: rgba(183,110,121,0.1);
        }
        .dark-theme .back-link {
            color: #888;
        }
        .dark-theme .alert-danger {
            background: #2d1520;
            border-left-color: #e74c3c;
            color: #f5a0a0;
        }
        .dark-theme .alert-success {
            background: #152d1a;
            border-left-color: #27ae60;
            color: #a0f5a8;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991px) {
            .auth-wrapper {
                flex-direction: column;
            }
            .auth-left {
                width: 100%;
                min-height: 240px;
                padding: 40px 30px;
            }
            .auth-right {
                width: 100%;
                padding: 40px 24px;
            }
            .left-content h2 {
                font-size: 24px;
            }
            .left-subtitle {
                display: none;
            }
            .left-features {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .auth-left {
                min-height: 200px;
                padding: 30px 20px;
            }
            .auth-right {
                padding: 30px 20px;
            }
            .left-icon {
                width: 60px;
                height: 60px;
                font-size: 24px;
                margin-bottom: 16px;
            }
            .left-content h2 {
                font-size: 20px;
            }
            .form-extras {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<div class="auth-wrapper">
    {{-- LEFT PANEL --}}
    <div class="auth-left">
        @yield('left')
    </div>

    {{-- RIGHT PANEL --}}
    <div class="auth-right">
        <div class="auth-right-inner">
            @yield('right')
        </div>
    </div>
</div>

<script>
// Theme toggle support
(function(){
    var THEME_KEY = 'salon-theme';
    var saved = localStorage.getItem(THEME_KEY);
    if (saved === 'dark') {
        document.body.classList.add('dark-theme');
    }
})();
</script>

@stack('scripts')

</body>
</html>
