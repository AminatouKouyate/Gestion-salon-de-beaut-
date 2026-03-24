{{--
    Vue : Layout principal - Espace Administration
    Description : Template de base pour toutes les pages d'administration : en-tête HTML, chargement des polices et CSS, thèmes de couleurs (rose-gold, ocean-blue, emerald, royal-purple, sunset), barre de navigation, sidebar, gestion du thème sombre et scripts globaux.
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KAARJA Beauté - Espace Administration</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"></noscript>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <script>
    (function(){
        @if($globalDarkMode ?? false)
            document.documentElement.classList.add('dark-theme');
        @endif
        @if(($globalColorTheme ?? 'rose-gold') !== 'rose-gold')
            document.documentElement.setAttribute('data-color-theme', '{{ $globalColorTheme }}');
        @endif
    })();
    </script>

    <style>
    /* ===================== COLOR THEMES ===================== */
    :root, [data-color-theme="rose-gold"] {
        --primary: #B76E79; --primary-light: #D4979F; --primary-soft: #F8E8EE;
        --accent: #D4AF37; --bg: #FFF8F0; --dark: #4A1942; --dark-light: #6B2D5B;
    }
    [data-color-theme="ocean-blue"] {
        --primary: #2E86AB; --primary-light: #5AAFCE; --primary-soft: #E3F2FD;
        --accent: #F6AE2D; --bg: #F5FAFE; --dark: #1A3A5C; --dark-light: #2D5F8A;
    }
    [data-color-theme="emerald"] {
        --primary: #2D8B61; --primary-light: #5DB88A; --primary-soft: #E8F5E9;
        --accent: #F0A500; --bg: #F5FFF8; --dark: #1B4332; --dark-light: #2D6A4F;
    }
    [data-color-theme="royal-purple"] {
        --primary: #7C3AED; --primary-light: #A78BFA; --primary-soft: #EDE9FE;
        --accent: #F59E0B; --bg: #FAF5FF; --dark: #3B0764; --dark-light: #5B21B6;
    }
    [data-color-theme="sunset"] {
        --primary: #E85D3A; --primary-light: #F0896E; --primary-soft: #FFF0ED;
        --accent: #F7B32B; --bg: #FFFAF5; --dark: #7C2D12; --dark-light: #B45309;
    }
    [data-color-theme="teal-coral"] {
        --primary: #0D9488; --primary-light: #5EEAD4; --primary-soft: #E6FFFA;
        --accent: #F87171; --bg: #F0FDFA; --dark: #134E4A; --dark-light: #1E6E68;
    }
    [data-color-theme="cherry"] {
        --primary: #DB2777; --primary-light: #F472B6; --primary-soft: #FDF2F8;
        --accent: #FBBF24; --bg: #FFFBFE; --dark: #831843; --dark-light: #9D174D;
    }
    [data-color-theme="slate"] {
        --primary: #475569; --primary-light: #94A3B8; --primary-soft: #F1F5F9;
        --accent: #3B82F6; --bg: #F8FAFC; --dark: #1E293B; --dark-light: #334155;
    }

    /* ===================== BASE ===================== */
    * { transition: background-color 0.3s, color 0.3s, border-color 0.3s, box-shadow 0.3s; }
    body {
        font-family: 'Poppins', sans-serif; background: var(--bg);
        color: #2D2D2D; padding-top: 80px; margin: 0;
    }
    h1,h2,h3,h4,h5,h6,.heading-font { font-family: 'Playfair Display', serif; }

    /* ===================== NAVBAR ===================== */
    .beauty-navbar {
        position: fixed; top: 0; left: 0; right: 0; z-index: 1050;
        background: rgba(255,255,255,0.88); backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border-bottom: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 2px 24px rgba(0,0,0,0.06);
    }
    .beauty-navbar .navbar-inner {
        display: flex; align-items: center; justify-content: space-between;
        max-width: 1400px; margin: 0 auto; padding: 0 24px; height: 72px;
    }
    .beauty-brand { display: flex; align-items: center; gap: 12px; text-decoration: none !important; }
    .beauty-brand img {
        width: 42px; height: 42px; border-radius: 50%; object-fit: cover;
        border: 2px solid var(--primary); box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .beauty-brand-name {
        font-family: 'Playfair Display', serif; font-weight: 700; font-size: 22px;
        background: linear-gradient(135deg, var(--primary), var(--dark));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }

    .beauty-nav { display: flex; align-items: center; gap: 4px; list-style: none; margin: 0; padding: 0; }
    .beauty-nav > li > a, .beauty-nav > li > .nav-dropdown-toggle {
        display: flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: 10px;
        font-size: 14px; font-weight: 500; color: #2D2D2D;
        text-decoration: none !important; cursor: pointer; border: none; background: none;
        transition: all 0.25s;
    }
    .beauty-nav > li > a:hover, .beauty-nav > li > .nav-dropdown-toggle:hover,
    .beauty-nav > li.active > a { background: var(--primary-soft); color: var(--primary); }
    .beauty-nav > li > a i, .beauty-nav > li > .nav-dropdown-toggle i { font-size: 15px; color: var(--primary); }

    .nav-dropdown { position: relative; }
    .nav-dropdown-menu {
        position: absolute; top: calc(100% + 8px); left: 0;
        background: white; border-radius: 14px; padding: 8px;
        box-shadow: 0 8px 35px rgba(0,0,0,0.12); min-width: 220px;
        opacity: 0; visibility: hidden; transform: translateY(-8px);
        transition: all 0.25s; z-index: 100;
    }
    .nav-dropdown.open .nav-dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
    .nav-dropdown-menu a {
        display: flex; align-items: center; gap: 10px; padding: 10px 14px;
        border-radius: 8px; font-size: 13px; color: #2D2D2D;
        text-decoration: none !important; transition: all 0.2s;
    }
    .nav-dropdown-menu a:hover { background: var(--primary-soft); color: var(--primary); }
    .nav-dropdown-menu a i { width: 18px; text-align: center; color: var(--primary); font-size: 14px; }

    .beauty-nav-right { display: flex; align-items: center; gap: 8px; }
    .nav-icon-btn {
        width: 42px; height: 42px; border-radius: 12px; border: none;
        background: var(--primary-soft); color: var(--primary);
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; cursor: pointer; position: relative; transition: all 0.25s;
    }
    .nav-icon-btn:hover { background: var(--primary); color: white; transform: translateY(-2px); }
    .nav-icon-btn .notif-badge {
        position: absolute; top: -4px; right: -4px;
        background: #E74C5F; color: white; font-size: 10px; font-weight: 700;
        min-width: 18px; height: 18px; border-radius: 9px;
        display: flex; align-items: center; justify-content: center; border: 2px solid white;
    }

    .nav-user-btn {
        display: flex; align-items: center; gap: 10px;
        padding: 6px 12px 6px 6px; border-radius: 14px; border: none;
        background: var(--primary-soft); cursor: pointer; transition: all 0.25s;
    }
    .nav-user-btn:hover { background: rgba(0,0,0,0.06); }
    .nav-user-btn img { width: 36px; height: 36px; border-radius: 10px; object-fit: cover; border: 2px solid var(--primary); }
    .nav-user-btn .user-placeholder {
        width: 36px; height: 36px; border-radius: 10px;
        background: linear-gradient(135deg, var(--primary), var(--dark));
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 15px; font-weight: 600;
    }
    .nav-user-btn .user-name { font-size: 13px; font-weight: 600; color: #2D2D2D; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    .profile-dropdown {
        position: absolute; top: calc(100% + 8px); right: 0;
        background: white; border-radius: 16px; padding: 8px;
        box-shadow: 0 8px 35px rgba(0,0,0,0.12); min-width: 240px;
        opacity: 0; visibility: hidden; transform: translateY(-8px);
        transition: all 0.25s; z-index: 100;
    }
    .profile-dropdown.open { opacity: 1; visibility: visible; transform: translateY(0); }
    .profile-dropdown-header { padding: 14px; border-bottom: 1px solid var(--primary-soft); margin-bottom: 6px; }
    .profile-dropdown-header h6 { margin: 0; font-size: 14px; font-weight: 600; }
    .profile-dropdown-header small { color: #8E8E8E; font-size: 12px; }
    .profile-dropdown a, .profile-dropdown button {
        display: flex; align-items: center; gap: 10px; width: 100%;
        padding: 10px 14px; border-radius: 10px; font-size: 13px;
        color: #2D2D2D; text-decoration: none !important;
        transition: all 0.2s; border: none; background: none; cursor: pointer; text-align: left;
    }
    .profile-dropdown a:hover, .profile-dropdown button:hover { background: var(--primary-soft); color: var(--primary); }
    .profile-dropdown .logout-btn { color: #E74C5F; }
    .profile-dropdown .logout-btn:hover { background: #FFF0F0; }

    .notif-dropdown {
        position: absolute; top: calc(100% + 8px); right: 0;
        background: white; border-radius: 16px; padding: 0;
        box-shadow: 0 8px 35px rgba(0,0,0,0.12); width: 340px;
        opacity: 0; visibility: hidden; transform: translateY(-8px);
        transition: all 0.25s; z-index: 100; overflow: hidden;
    }
    .notif-dropdown.open { opacity: 1; visibility: visible; transform: translateY(0); }
    .notif-dropdown-header {
        padding: 16px 18px; background: linear-gradient(135deg, var(--primary), var(--dark));
        color: white; font-weight: 600; font-size: 14px;
    }
    .notif-dropdown-body { max-height: 320px; overflow-y: auto; }
    .notif-item { display: block; padding: 14px 18px; border-bottom: 1px solid var(--primary-soft); text-decoration: none !important; color: #2D2D2D; transition: all 0.2s; }
    .notif-item:hover { background: var(--bg); color: #2D2D2D; }
    .notif-item h6 { font-size: 13px; margin: 0 0 3px; font-weight: 600; }
    .notif-item p { font-size: 12px; color: #8E8E8E; margin: 0; }
    .notif-item small { font-size: 11px; color: var(--primary); }
    .notif-footer { padding: 12px 18px; text-align: center; border-top: 1px solid var(--primary-soft); }
    .notif-footer a { color: var(--primary); font-size: 13px; font-weight: 500; text-decoration: none; }

    /* Mobile */
    .mobile-toggle {
        display: none; width: 42px; height: 42px; border-radius: 10px; border: none;
        background: var(--primary-soft); color: var(--primary);
        font-size: 20px; cursor: pointer; align-items: center; justify-content: center;
    }
    .mobile-menu {
        display: none; position: fixed; top: 72px; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255,0.98); backdrop-filter: blur(20px);
        z-index: 1040; padding: 20px; overflow-y: auto; animation: slideDown 0.3s ease;
    }
    .mobile-menu.show { display: block; }
    .mobile-menu a {
        display: flex; align-items: center; gap: 12px;
        padding: 14px 18px; border-radius: 12px; font-size: 15px; font-weight: 500;
        color: #2D2D2D; text-decoration: none; margin-bottom: 4px; transition: all 0.25s;
    }
    .mobile-menu a:hover { background: var(--primary-soft); color: var(--primary); }
    .mobile-menu a i { width: 22px; text-align: center; color: var(--primary); }
    .mobile-menu .mobile-section {
        font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px;
        color: #8E8E8E; padding: 16px 18px 6px; font-weight: 600;
    }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 991px) {
        .beauty-nav, .beauty-nav-right .nav-icon-btn, .beauty-nav-right .nav-user-wrapper { display: none !important; }
        .mobile-toggle { display: flex; }
        body { padding-top: 72px; }
    }

    /* Content — override template sidebar margin */
    .admin-content { min-height: calc(100vh - 72px - 80px); padding: 30px 0; }
    .admin-content .container-fluid { max-width: 1400px; margin: 0 auto; padding: 0 24px; }
    .content-body { margin-left: 0 !important; }
    .nk-sidebar, .nav-header, .quixnav, .deznav { display: none !important; }

    /* ===================== BEAUTY BOOTSTRAP OVERRIDES ===================== */

    /* Force container-fluid max-width partout */
    .content-body .container-fluid { max-width: 1400px; margin: 0 auto; padding: 0 24px; }

    /* Page titles / headers */
    .page-titles { padding: 8px 0 24px; margin-bottom: 0 !important; border: none; }
    .page-titles .welcome-text h4 {
        font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700;
        color: var(--dark); margin: 0;
    }
    .page-titles .welcome-text p { color: #8E8E8E; font-size: 14px; margin: 4px 0 0; }
    .content-body h1 {
        font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: var(--dark);
    }
    .content-body h2, .content-body h3, .content-body h5 {
        font-family: 'Playfair Display', serif; color: var(--dark);
    }

    /* Cards */
    .card {
        border: none !important; border-radius: 18px !important; overflow: hidden;
        box-shadow: 0 2px 16px rgba(0,0,0,0.06); transition: all 0.3s;
    }
    .card:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.1); }
    .card-header {
        background: white !important; border-bottom: 1px solid var(--primary-soft) !important;
        padding: 18px 24px !important;
    }
    .card-header .card-title, .card-header h4, .card-header h5 {
        font-family: 'Playfair Display', serif; font-size: 17px;
        font-weight: 600; color: var(--dark) !important; margin: 0;
    }
    .card-body { padding: 24px; }
    .card-footer { background: white !important; border-top: 1px solid var(--primary-soft) !important; padding: 16px 24px; }

    /* Cartes stat colorées — unifier avec seulement 2 couleurs du thème */
    .card.gradient-1, .card.gradient-2, .card.gradient-3, .card.gradient-4 {
        border-radius: 18px !important;
    }
    .card.gradient-1 { background: linear-gradient(135deg, var(--primary), var(--dark)) !important; }
    .card.gradient-2 { background: linear-gradient(135deg, var(--primary-light), var(--primary)) !important; }
    .card.gradient-3 { background: linear-gradient(135deg, var(--dark-light), var(--dark)) !important; }
    .card.gradient-4 { background: linear-gradient(135deg, var(--accent), var(--primary)) !important; }
    .card.gradient-1 .card-header, .card.gradient-2 .card-header,
    .card.gradient-3 .card-header, .card.gradient-4 .card-header {
        background: transparent !important; border-bottom: none !important;
    }
    .card.gradient-1 h2, .card.gradient-2 h2, .card.gradient-3 h2, .card.gradient-4 h2 {
        font-family: 'Playfair Display', serif; font-size: 32px; font-weight: 700;
    }
    .card.gradient-1 h3, .card.gradient-2 h3, .card.gradient-3 h3, .card.gradient-4 h3 {
        font-size: 14px; font-weight: 500; opacity: 0.9; color: white !important;
    }
    .card.gradient-1 .display-5, .card.gradient-2 .display-5,
    .card.gradient-3 .display-5, .card.gradient-4 .display-5 {
        font-size: 48px; opacity: 0.15 !important;
    }

    /* bg-primary/success/warning/info cards — utiliser les couleurs du thème */
    .card.bg-primary { background: linear-gradient(135deg, var(--primary), var(--dark)) !important; }
    .card.bg-success { background: linear-gradient(135deg, var(--primary-light), var(--primary)) !important; }
    .card.bg-info { background: linear-gradient(135deg, var(--dark-light), var(--dark)) !important; }
    .card.bg-warning { background: linear-gradient(135deg, var(--accent), var(--primary)) !important; color: white !important; }
    .card.bg-primary .card-header, .card.bg-success .card-header,
    .card.bg-info .card-header, .card.bg-warning .card-header {
        background: transparent !important; border-bottom: none !important;
    }
    .card.bg-primary h3, .card.bg-primary h5, .card.bg-primary small,
    .card.bg-success h3, .card.bg-success h5, .card.bg-success small,
    .card.bg-info h3, .card.bg-info h5, .card.bg-info small,
    .card.bg-warning h3, .card.bg-warning h5, .card.bg-warning small {
        color: white !important;
    }
    .card.bg-warning .text-dark { color: white !important; }

    /* Tables */
    .table { border-collapse: separate; border-spacing: 0; }
    .table thead th {
        background: var(--primary-soft); color: var(--dark); font-size: 12px;
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;
        padding: 14px 16px; border: none !important;
    }
    .table thead th:first-child { border-radius: 12px 0 0 12px; }
    .table thead th:last-child { border-radius: 0 12px 12px 0; }
    .table tbody td {
        padding: 14px 16px; font-size: 14px; vertical-align: middle;
        border-bottom: 1px solid rgba(0,0,0,0.04) !important; border-top: none !important; color: #2D2D2D;
    }
    .table-bordered td, .table-bordered th { border: none !important; border-bottom: 1px solid rgba(0,0,0,0.04) !important; }
    .table-bordered th { background: var(--primary-soft); color: var(--dark); font-weight: 700; width: 180px; }
    .table tbody tr { transition: all 0.2s; }
    .table tbody tr:hover { background: var(--bg) !important; }
    .table-striped tbody tr:nth-of-type(odd) { background: rgba(0,0,0,0.01); }
    .table-striped tbody tr:nth-of-type(odd):hover { background: var(--bg) !important; }
    .text-primary { color: var(--primary) !important; }

    /* Buttons */
    .btn { border-radius: 12px; font-weight: 600; font-size: 14px; padding: 10px 20px; transition: all 0.3s; border: none !important; }
    .btn-primary, .btn-primary:focus {
        background: linear-gradient(135deg, var(--primary), var(--dark)) !important;
        color: white !important; box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 22px rgba(0,0,0,0.2); }
    .btn-success, .btn-success:focus {
        background: linear-gradient(135deg, var(--primary-light), var(--primary)) !important;
        color: white !important; box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    }
    .btn-success:hover { transform: translateY(-2px); box-shadow: 0 6px 22px rgba(0,0,0,0.18); }
    .btn-danger, .btn-danger:focus {
        background: linear-gradient(135deg, #ef4444, #dc2626) !important;
        color: white !important; box-shadow: 0 4px 15px rgba(239,68,68,0.25);
    }
    .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 6px 22px rgba(239,68,68,0.35); }
    .btn-warning, .btn-warning:focus {
        background: linear-gradient(135deg, var(--accent), var(--primary)) !important;
        color: white !important; box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    }
    .btn-warning:hover { transform: translateY(-2px); color: white !important; }
    .btn-info, .btn-info:focus {
        background: linear-gradient(135deg, var(--dark-light), var(--dark)) !important;
        color: white !important; box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    }
    .btn-info:hover { transform: translateY(-2px); color: white !important; }
    .btn-secondary, .btn-secondary:focus, .btn-light, .btn-light:focus {
        background: var(--primary-soft) !important; color: var(--dark) !important; box-shadow: none;
    }
    .btn-secondary:hover, .btn-light:hover { background: var(--primary-light) !important; color: white !important; transform: translateY(-2px); }
    .btn-outline-primary {
        border: 2px solid var(--primary) !important; color: var(--primary) !important; background: transparent !important;
    }
    .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary.active {
        background: linear-gradient(135deg, var(--primary), var(--dark)) !important;
        color: white !important; transform: translateY(-2px);
    }
    .btn-outline-secondary {
        border: 2px solid var(--primary-light) !important; color: var(--dark) !important; background: transparent !important;
    }
    .btn-outline-secondary:hover { background: var(--primary-soft) !important; color: var(--primary) !important; transform: translateY(-2px); }
    .btn-outline-danger {
        border: 2px solid #ef4444 !important; color: #ef4444 !important; background: transparent !important;
    }
    .btn-outline-danger:hover { background: #ef4444 !important; color: white !important; transform: translateY(-2px); }
    .btn-sm { padding: 6px 14px; font-size: 13px; border-radius: 10px; }
    .btn-group .btn { border-radius: 10px; margin-right: 2px; }
    .btn-group .btn:first-child { border-radius: 10px 0 0 10px; }
    .btn-group .btn:last-child { border-radius: 0 10px 10px 0; margin-right: 0; }

    /* Forms */
    .form-control, .form-select {
        border: 2px solid rgba(0,0,0,0.08) !important; border-radius: 12px !important;
        padding: 10px 16px; font-size: 14px; transition: all 0.3s;
        background: white;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary) !important; box-shadow: 0 0 0 3px var(--primary-soft) !important;
        outline: none;
    }
    .form-label, label:not(.btn):not(.form-check-label) { font-weight: 600; font-size: 14px; color: var(--dark); margin-bottom: 6px; }
    select.form-control, .form-select {
        appearance: none; -webkit-appearance: none; -moz-appearance: none;
        background-repeat: no-repeat; background-position: right 14px center; background-size: 16px;
        padding-right: 40px; cursor: pointer; height: auto; min-height: 44px;
        line-height: 1.5;
    }
    select.form-control:hover, .form-select:hover {
        border-color: var(--primary-light) !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    select.form-control option, .form-select option {
        padding: 10px 14px; font-size: 14px; background: white; color: #2D2D2D;
    }
    select.form-control option:checked, .form-select option:checked {
        background: var(--primary-soft); color: var(--primary);
    }
    textarea.form-control { border-radius: 14px !important; }
    .input-group-text {
        border: 2px solid rgba(0,0,0,0.08); border-radius: 12px 0 0 12px !important;
        background: var(--primary-soft); color: var(--dark); font-size: 14px;
    }
    .input-group .form-control { border-radius: 0 12px 12px 0 !important; }
    .form-check-input:checked { background-color: var(--primary); border-color: var(--primary); }

    /* Badges — palette cohérente basée sur le thème */
    .badge {
        padding: 5px 12px; border-radius: 8px; font-weight: 600;
        font-size: 12px; letter-spacing: 0.3px;
    }
    .badge-primary, .bg-primary:not(.card) { background: var(--primary-soft) !important; color: var(--primary) !important; }
    .badge-success, .bg-success:not(.card) { background: #d1fae5 !important; color: #059669 !important; }
    .badge-danger, .bg-danger:not(.card) { background: #fee2e2 !important; color: #dc2626 !important; }
    .badge-warning, .bg-warning:not(.card) { background: #fef3c7 !important; color: #92400e !important; }
    .badge-info, .bg-info:not(.card) { background: var(--primary-soft) !important; color: var(--dark) !important; }
    .badge-secondary, .bg-secondary:not(.card) { background: var(--primary-soft) !important; color: var(--dark) !important; }
    .badge-pill { border-radius: 20px; }
    .badge-lg { padding: 8px 16px; font-size: 13px; }

    /* Alerts */
    .alert { border: none !important; border-radius: 14px; font-size: 14px; padding: 16px 20px; }
    .alert-success { background: linear-gradient(135deg, #d1fae5, #a7f3d0) !important; color: #065f46 !important; }
    .alert-danger { background: linear-gradient(135deg, #fee2e2, #fecaca) !important; color: #991b1b !important; }
    .alert-warning { background: linear-gradient(135deg, #fef3c7, #fde68a) !important; color: #92400e !important; }
    .alert-info { background: var(--primary-soft) !important; color: var(--dark) !important; }

    /* Modals */
    .modal-content { border: none !important; border-radius: 20px !important; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
    .modal-header {
        background: linear-gradient(135deg, var(--primary), var(--dark)) !important;
        color: white !important; border-bottom: none !important; padding: 20px 24px;
    }
    .modal-header .modal-title, .modal-header h5 { font-family: 'Playfair Display', serif; font-weight: 600; color: white !important; }
    .modal-header .close { color: white !important; opacity: 0.8; text-shadow: none; }
    .modal-header .close:hover { opacity: 1; }
    .modal-body { padding: 24px; }
    .modal-footer { border-top: 1px solid var(--primary-soft) !important; padding: 16px 24px; }

    /* Breadcrumb */
    .breadcrumb { background: var(--primary-soft); border-radius: 12px; padding: 10px 18px; margin: 0; }
    .breadcrumb-item a { color: var(--primary); font-weight: 500; }
    .breadcrumb-item.active { color: var(--dark); font-weight: 600; }

    /* Pagination */
    .pagination { gap: 4px; }
    .page-item .page-link {
        border: none !important; border-radius: 10px !important; padding: 8px 14px;
        font-size: 14px; font-weight: 500; color: var(--dark); transition: all 0.25s;
    }
    .page-item .page-link:hover { background: var(--primary-soft); color: var(--primary); }
    .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary), var(--dark)) !important;
        color: white !important; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .page-item.disabled .page-link { opacity: 0.4; }

    /* Profile */
    .profile-photo img { border: 3px solid var(--primary); box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    .avatar { background: linear-gradient(135deg, var(--primary), var(--dark)) !important; }

    /* List group */
    .list-group-item { border-radius: 12px !important; border: 1px solid rgba(0,0,0,0.06) !important; margin-bottom: 4px; transition: all 0.2s; }
    .list-group-item:hover { background: var(--bg); }
    .list-group-item.active { background: var(--primary-soft) !important; border-color: var(--primary) !important; color: var(--dark) !important; }

    /* Spinner */
    .spinner-border { color: var(--primary) !important; }

    /* Vide */
    .text-center.py-4 i.fa, .text-center.py-5 i.fa { color: var(--primary-light) !important; opacity: 0.4; }

    /* ===================== BEAUTY COMPONENTS (réutilisables) ===================== */

    /* Page header */
    .beauty-page-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 24px 0 30px; flex-wrap: wrap; gap: 16px;
    }
    .beauty-page-header-left { display: flex; align-items: center; gap: 16px; }
    .beauty-page-icon {
        width: 64px; height: 64px; border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, var(--primary), var(--dark));
        color: white; font-size: 26px;
        border: 3px solid var(--primary); box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    }
    .beauty-page-title {
        font-family: 'Playfair Display', serif; font-size: 26px; font-weight: 700;
        color: var(--dark); margin: 0;
    }
    .beauty-page-subtitle { color: #8E8E8E; font-size: 14px; margin: 4px 0 0; }
    .beauty-btn-primary {
        display: inline-flex; align-items: center; padding: 12px 28px;
        background: linear-gradient(135deg, var(--primary), var(--dark));
        color: white !important; border: none; border-radius: 14px; font-size: 14px; font-weight: 600;
        text-decoration: none !important; transition: all 0.3s; cursor: pointer;
        box-shadow: 0 4px 18px rgba(0,0,0,0.15);
    }
    .beauty-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(0,0,0,0.2); color: white; }

    /* Stat card */
    .beauty-stat {
        background: white; border-radius: 18px; padding: 24px;
        display: flex; align-items: center; gap: 16px;
        border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.3s;
    }
    .beauty-stat:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
    .beauty-stat-icon {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; flex-shrink: 0;
    }
    .beauty-stat-icon.rose { background: var(--primary-soft); color: var(--primary); }
    .beauty-stat-icon.green { background: #d1fae5; color: #059669; }
    .beauty-stat-icon.gold { background: #fef3c7; color: #d97706; }
    .beauty-stat-icon.plum { background: #f0e5f5; color: var(--dark); }
    .beauty-stat-icon.red { background: #fee2e2; color: #dc2626; }
    .beauty-stat-icon.purple { background: #ede9fe; color: #7c3aed; }
    .beauty-stat h3 { font-family: 'Playfair Display', serif; font-size: 24px; margin: 0; color: var(--dark); }
    .beauty-stat p { font-size: 13px; color: #8E8E8E; margin: 2px 0 0; }

    /* Revenue / gradient card */
    .beauty-gradient-card {
        border-radius: 18px; padding: 28px; display: flex; align-items: center; gap: 20px;
        color: white; transition: all 0.3s;
    }
    .beauty-gradient-card:hover { transform: translateY(-3px); }
    .beauty-gradient-card.green { background: linear-gradient(135deg, #34d399, #10b981); box-shadow: 0 4px 20px rgba(16,185,129,0.2); }
    .beauty-gradient-card.purple { background: linear-gradient(135deg, #a78bfa, #7c3aed); box-shadow: 0 4px 20px rgba(124,58,237,0.2); }
    .beauty-gradient-card.primary { background: linear-gradient(135deg, var(--primary-light), var(--primary)); box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    .beauty-gradient-icon {
        width: 56px; height: 56px; border-radius: 16px; background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;
    }
    .beauty-gradient-card .info p { margin: 0 0 4px; font-size: 14px; opacity: 0.85; }
    .beauty-gradient-card .info h3 { margin: 0; font-family: 'Playfair Display', serif; font-size: 26px; font-weight: 700; color: white; }
    .beauty-gradient-card .info h3 span { font-size: 14px; font-weight: 400; opacity: 0.8; }

    /* Beauty card (container) */
    .beauty-card {
        background: white; border-radius: 18px; overflow: hidden;
        border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
    .beauty-card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 24px; border-bottom: 1px solid rgba(0,0,0,0.06);
    }
    .beauty-card-header h4 {
        font-family: 'Playfair Display', serif; font-size: 18px; margin: 0; color: var(--dark);
    }
    .beauty-card-body { padding: 20px 24px; }
    .beauty-card-footer { padding: 16px 24px; border-top: 1px solid rgba(0,0,0,0.06); }
    .beauty-link { font-size: 13px; font-weight: 600; color: var(--primary); text-decoration: none; }
    .beauty-link:hover { color: var(--dark); text-decoration: none; }

    /* Empty state */
    .beauty-empty {
        text-align: center; padding: 40px 20px;
    }
    .beauty-empty-icon {
        width: 80px; height: 80px; border-radius: 20px; background: var(--primary-soft);
        display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;
    }
    .beauty-empty-icon i { font-size: 28px; color: var(--primary); opacity: 0.5; }
    .beauty-empty p { color: #8E8E8E; font-size: 15px; }

    /* Image thumbnail */
    .beauty-thumb {
        width: 50px; height: 50px; object-fit: cover; border-radius: 12px;
        border: 2px solid var(--primary-soft);
    }
    .beauty-thumb-placeholder {
        width: 50px; height: 50px; border-radius: 12px; background: var(--primary-soft);
        display: flex; align-items: center; justify-content: center;
    }
    .beauty-thumb-placeholder i { color: var(--primary-light); }

    /* Dark mode — beauty components */
    .dark-theme .beauty-stat, .dark-theme .beauty-card { background: #252540; border-color: #333355; }
    .dark-theme .beauty-stat h3, .dark-theme .beauty-card-header h4,
    .dark-theme .beauty-page-title { color: #E8E8E8 !important; }
    .dark-theme .beauty-card-header { border-bottom-color: #333355; }
    .dark-theme .beauty-card-footer { border-top-color: #333355; }
    .dark-theme .beauty-empty-icon { background: #2a2555; }
    .dark-theme .beauty-thumb-placeholder { background: #2a2555; }

    /* Chart.js canvas */
    canvas { border-radius: 12px; }

    /* Calendar FullCalendar */
    .fc .fc-button-primary {
        background: var(--primary) !important; border-color: var(--primary) !important;
        border-radius: 8px !important;
    }
    .fc .fc-button-primary:hover { background: var(--dark) !important; }
    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background: var(--dark) !important;
    }
    .fc .fc-toolbar-title { font-family: 'Playfair Display', serif; color: var(--dark); }

    /* ===================== RESPONSIVE ===================== */
    @media (max-width: 767px) {
        body { padding-top: 72px; }
        .content-body .container-fluid, .admin-content .container-fluid { padding: 0 14px; }
        .beauty-page-header { padding: 16px 0 20px; flex-direction: column; align-items: flex-start; gap: 12px; }
        .beauty-page-icon { width: 48px; height: 48px; border-radius: 14px; font-size: 20px; }
        .beauty-page-title { font-size: 20px; }
        .beauty-page-subtitle { font-size: 13px; }
        .beauty-btn-primary { padding: 10px 20px; font-size: 13px; width: 100%; justify-content: center; }
        .card { border-radius: 14px !important; }
        .card-body { padding: 16px; }
        .card-header { padding: 14px 16px !important; }
        .beauty-card-body { padding: 14px 16px; }
        .beauty-card-header { padding: 14px 16px; }
        .beauty-stat { padding: 16px; gap: 12px; border-radius: 14px; }
        .beauty-stat-icon { width: 44px; height: 44px; border-radius: 12px; font-size: 18px; }
        .beauty-stat h3 { font-size: 20px; }
        .beauty-empty { padding: 24px 14px; }
        .beauty-empty i { font-size: 36px; }
        .table thead th { font-size: 11px; padding: 10px 8px; letter-spacing: 0.5px; }
        .table tbody td { padding: 10px 8px; font-size: 13px; }
        .table-responsive { margin: 0 -16px; padding: 0 16px; }
        .btn { padding: 8px 14px; font-size: 13px; border-radius: 10px; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-group { flex-wrap: wrap; gap: 4px; }
        .modal-content { border-radius: 14px !important; margin: 10px; }
        .modal-body { padding: 16px; }
        .form-control, .form-select { padding: 8px 14px; font-size: 13px; min-height: 40px; }
        select.form-control, .form-select { min-height: 40px; }
        .alert { padding: 12px 16px; font-size: 13px; border-radius: 12px; }
        .pagination { flex-wrap: wrap; }
        .page-item .page-link { padding: 6px 10px; font-size: 13px; }
        h1, .content-body h1 { font-size: 20px !important; }
        h2, .content-body h2 { font-size: 18px !important; }
        .row > [class*="col-lg"], .row > [class*="col-md"] { margin-bottom: 16px; }
    }
    @media (max-width: 575px) {
        .beauty-page-header-left { gap: 10px; }
        .beauty-page-icon { width: 42px; height: 42px; font-size: 18px; }
        .beauty-page-title { font-size: 18px; }
        .table thead th { padding: 8px 6px; font-size: 10px; }
        .table tbody td { padding: 8px 6px; font-size: 12px; }
        .beauty-stat { flex-direction: column; text-align: center; }
    }

    @media (max-width: 991px) {
        .beauty-page-header { flex-direction: column; align-items: flex-start; }
        .beauty-btn-primary { width: 100%; justify-content: center; }
    }
    @media (max-width: 480px) {
        .admin-content { padding: 16px 0; }
        .admin-content .container-fluid, .content-body .container-fluid { padding: 0 10px; }
        .beauty-card { border-radius: 14px; }
        .beauty-card-header { padding: 12px 14px; flex-wrap: wrap; gap: 8px; }
        .beauty-card-header h4 { font-size: 15px; }
        .beauty-card-body { padding: 12px 14px; }
        .beauty-card-footer { padding: 12px 14px; }
        .btn-group { display: flex; flex-wrap: wrap; gap: 4px; }
        .btn-group .btn { border-radius: 8px !important; flex: 1; min-width: 0; }
        .form-control, .form-select { font-size: 16px; }
        .table { font-size: 12px; }
        .table thead th { white-space: nowrap; }
        .modal-dialog { margin: 8px; }
        .beauty-page-title { font-size: 17px; }
        .beauty-page-subtitle { font-size: 12px; }
        .notif-dropdown { width: calc(100vw - 20px) !important; right: -60px !important; }
    }

    /* Dark mode - Bootstrap overrides */
    .dark-theme .card:not(.gradient-1):not(.gradient-2):not(.gradient-3):not(.gradient-4):not(.bg-primary):not(.bg-success):not(.bg-info):not(.bg-warning) { background: #252540 !important; }
    .dark-theme .card-header { background: #2a2555 !important; border-bottom-color: #333355 !important; }
    .dark-theme .card-header .card-title, .dark-theme .card-header h4, .dark-theme .card-header h5 { color: #E8E8E8 !important; }
    .dark-theme .card-body { color: #E8E8E8; }
    .dark-theme .card-footer { background: #252540 !important; border-top-color: #333355 !important; }
    .dark-theme .table thead th { background: #2a2555 !important; color: #E8E8E8; }
    .dark-theme .table tbody td { color: #ccc; border-bottom-color: #333355 !important; }
    .dark-theme .table tbody tr:hover { background: #2a2040 !important; }
    .dark-theme .table-striped tbody tr:nth-of-type(odd) { background: rgba(255,255,255,0.02); }
    .dark-theme .form-control, .dark-theme .form-select { background: #1e1e30 !important; border-color: #333355 !important; color: #E8E8E8 !important; }
    .dark-theme .form-control:focus, .dark-theme .form-select:focus { border-color: var(--primary) !important; }
    .dark-theme select.form-control, .dark-theme .form-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23D4979F' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important;
    }
    .dark-theme select.form-control option, .dark-theme .form-select option { background: #1e1e30; color: #E8E8E8; }
    .dark-theme .input-group-text { background: #2a2555; border-color: #333355; color: #E8E8E8; }
    .dark-theme .btn-secondary, .dark-theme .btn-light { background: #2a2555 !important; color: #E8E8E8 !important; }
    .dark-theme .modal-content { background: #1e1e30 !important; }
    .dark-theme .modal-body { color: #E8E8E8; }
    .dark-theme .modal-footer { border-top-color: #333355 !important; }
    .dark-theme .page-titles .welcome-text h4, .dark-theme .content-body h1,
    .dark-theme .content-body h2, .dark-theme .content-body h3, .dark-theme .content-body h5 { color: #E8E8E8 !important; }
    .dark-theme .breadcrumb { background: #2a2555; }
    .dark-theme .breadcrumb-item.active { color: #E8E8E8; }
    .dark-theme .page-item .page-link { color: #E8E8E8; }
    .dark-theme .page-item .page-link:hover { background: #2a2555; }
    .dark-theme .badge-primary, .dark-theme .badge-info, .dark-theme .badge-secondary { background: rgba(255,255,255,0.1) !important; }
    .dark-theme label:not(.btn):not(.form-check-label), .dark-theme .form-label { color: #E8E8E8 !important; }
    .dark-theme .text-muted { color: #9E9E9E !important; }
    .dark-theme .list-group-item { background: #2a2040 !important; border-color: #333355 !important; color: #E8E8E8; }
    .dark-theme .list-group-item:hover { background: #332850 !important; }
    .dark-theme select.form-control, .dark-theme .form-select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%239E9E9E' d='M6 8L1 3h10z'/%3E%3C/svg%3E") !important; }
    .dark-theme .fc .fc-toolbar-title { color: #E8E8E8; }

    /* Footer */
    .beauty-footer {
        background: linear-gradient(135deg, var(--dark), #1a1020);
        color: rgba(255,255,255,0.7); padding: 40px 0 20px;
    }
    .beauty-footer h5 { font-family: 'Playfair Display', serif; color: var(--primary-light); margin-bottom: 16px; }
    .beauty-footer a { color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s; }
    .beauty-footer a:hover { color: var(--primary-light); }
    .beauty-footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); margin-top: 30px; padding-top: 20px; text-align: center; font-size: 13px; }
    .beauty-footer .footer-brand { font-family: 'Playfair Display', serif; font-size: 24px; color: white; }
    .beauty-footer .social-link {
        display: inline-flex; align-items: center; justify-content: center;
        width: 38px; height: 38px; border-radius: 50%; background: rgba(255,255,255,0.1);
        color: white; margin-right: 8px; transition: all 0.3s;
    }
    .beauty-footer .social-link:hover { background: var(--primary); transform: translateY(-3px); }

    /* =================== THEME SETTINGS PANEL =================== */

    .theme-panel-overlay {
        display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.4); z-index: 10000;
        animation: fadeIn 0.3s ease;
    }
    .theme-panel-overlay.open { display: block; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    .theme-panel {
        position: fixed; top: 0; right: -400px; bottom: 0; width: 380px;
        background: white; z-index: 10001; overflow-y: auto;
        box-shadow: -10px 0 40px rgba(0,0,0,0.15);
        transition: right 0.4s cubic-bezier(0.4,0,0.2,1);
        padding: 0;
    }
    .theme-panel.open { right: 0; }

    .theme-panel-header {
        padding: 24px; background: linear-gradient(135deg, var(--primary), var(--dark));
        color: white; position: sticky; top: 0; z-index: 2;
    }
    .theme-panel-header h4 {
        font-family: 'Playfair Display', serif; margin: 0 0 4px; font-size: 20px; color: white;
    }
    .theme-panel-header p { margin: 0; opacity: 0.8; font-size: 13px; }
    .theme-panel-close {
        position: absolute; top: 20px; right: 20px;
        background: rgba(255,255,255,0.2); border: none; color: white;
        width: 36px; height: 36px; border-radius: 50%; font-size: 18px;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: all 0.2s;
    }
    .theme-panel-close:hover { background: rgba(255,255,255,0.3); }

    .theme-panel-body { padding: 24px; }
    .theme-section { margin-bottom: 28px; }
    .theme-section-title {
        font-size: 12px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1.5px; color: #8E8E8E; margin-bottom: 14px;
        display: flex; align-items: center; gap: 8px;
    }
    .theme-section-title i { color: var(--primary); }

    .color-themes-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .color-theme-option {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 14px; border-radius: 12px; border: 2px solid #EEE;
        cursor: pointer; transition: all 0.25s; background: white;
    }
    .color-theme-option:hover { border-color: var(--primary-light); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .color-theme-option.active { border-color: var(--primary); background: var(--primary-soft); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .color-theme-dots { display: flex; gap: 3px; }
    .color-theme-dots span { width: 16px; height: 16px; border-radius: 50%; border: 1px solid rgba(0,0,0,0.1); }
    .color-theme-label { font-size: 12px; font-weight: 600; color: #333; }

    /* Dark mode toggle in panel */
    .dark-mode-toggle {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 18px; border-radius: 14px; background: #F5F5F5;
        cursor: pointer; transition: all 0.25s;
    }
    .dark-mode-toggle:hover { background: #EFEFEF; }
    .dark-mode-toggle .toggle-info { display: flex; align-items: center; gap: 12px; }
    .dark-mode-toggle .toggle-icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: linear-gradient(135deg, #1a1a2e, #16213e);
        color: #FFD700; display: flex; align-items: center; justify-content: center; font-size: 18px;
    }
    .dark-mode-toggle .toggle-label { font-size: 14px; font-weight: 600; color: #333; }
    .dark-mode-toggle .toggle-desc { font-size: 11px; color: #999; }
    .switch-track {
        width: 48px; height: 26px; border-radius: 13px; background: #D1D5DB;
        position: relative; transition: all 0.3s; flex-shrink: 0;
    }
    .switch-track.on { background: var(--primary); }
    .switch-track::after {
        content: ''; position: absolute; top: 3px; left: 3px;
        width: 20px; height: 20px; border-radius: 50%; background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2); transition: all 0.3s;
    }
    .switch-track.on::after { left: 25px; }

    /* Dark mode overrides */
    .dark-theme { background: #1a1a2e; color: #E8E8E8; }
    .dark-theme body { background: #1a1a2e; color: #E8E8E8; }
    .dark-theme .beauty-navbar { background: rgba(26,26,46,0.92); border-bottom-color: rgba(255,255,255,0.06); }
    .dark-theme .nav-dropdown-menu, .dark-theme .profile-dropdown, .dark-theme .notif-dropdown { background: #252540; }
    .dark-theme .nav-dropdown-menu a:hover, .dark-theme .profile-dropdown a:hover { background: #2a2040; }
    .dark-theme .notif-item { border-bottom-color: #333; }
    .dark-theme .notif-item:hover { background: #2a2040; }
    .dark-theme .nav-user-btn { background: #2a2040; }
    .dark-theme .nav-user-btn .user-name { color: #E8E8E8; }
    .dark-theme .card { background: #252540; border-color: #333355; }
    .dark-theme .card-header { background: #2a2555 !important; border-bottom-color: #333355; }
    .dark-theme .mobile-menu { background: rgba(26,26,46,0.98); }
    .dark-theme .mobile-menu a { color: #E8E8E8; }
    .dark-theme .mobile-menu a:hover { background: #2a2040; }
    .dark-theme .profile-dropdown-header { border-bottom-color: #333; }
    .dark-theme .notif-footer { border-top-color: #333; }
    .dark-theme .theme-panel { background: #1e1e30; }
    .dark-theme .color-theme-option { background: #252540; border-color: #333355; }
    .dark-theme .color-theme-option:hover { border-color: var(--primary); }
    .dark-theme .color-theme-option.active { background: #2a2555; }
    .dark-theme .color-theme-label { color: #E8E8E8; }
    .dark-theme .dark-mode-toggle { background: #252540; }
    .dark-theme .dark-mode-toggle .toggle-label { color: #E8E8E8; }
    .dark-theme .beauty-nav > li > a, .dark-theme .beauty-nav > li > .nav-dropdown-toggle { color: #E8E8E8; }
    .dark-theme .nav-dropdown-menu a { color: #E8E8E8; }
    .dark-theme .profile-dropdown a, .dark-theme .profile-dropdown button { color: #E8E8E8; }
    .dark-theme .profile-dropdown-header h6 { color: #E8E8E8; }
    </style>
    @stack('styles')
</head>

<body>

{{-- ============== NAVBAR ============== --}}
<nav class="beauty-navbar">
    <div class="navbar-inner">
        <a href="{{ route('admin.dashboard') }}" class="beauty-brand">
            <img src="{{ asset('images/image1.jpg') }}" alt="Logo">
            <span class="beauty-brand-name">KAARJA Beauté</span>
            <span style="font-size:11px;padding:3px 10px;border-radius:20px;background:linear-gradient(135deg,var(--primary),var(--dark));color:white;margin-left:8px;font-family:'Poppins',sans-serif;">Admin</span>
        </a>

        <ul class="beauty-nav">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i> Accueil</a>
            </li>
            <li class="nav-dropdown {{ request()->routeIs('admin.appointments.*','admin.schedules.*') ? 'active' : '' }}">
                <button class="nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                    <i class="fa fa-calendar"></i> RDV <i class="fa fa-angle-down" style="font-size:12px;"></i>
                </button>
                <div class="nav-dropdown-menu">
                    <a href="{{ route('admin.appointments.index') }}"><i class="fa fa-list"></i> Tous les RDV</a>
                    <a href="{{ route('admin.appointments.create') }}"><i class="fa fa-plus-circle"></i> Nouveau RDV</a>
                    <a href="{{ route('admin.schedules.index') }}"><i class="fa fa-clock-o"></i> Planning</a>
                </div>
            </li>
            <li class="nav-dropdown {{ request()->routeIs('admin.employees.*','admin.clients.*','admin.leaves.*','admin.employee-messages.*') ? 'active' : '' }}">
                <button class="nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                    <i class="fa fa-users"></i> Personnes <i class="fa fa-angle-down" style="font-size:12px;"></i>
                </button>
                <div class="nav-dropdown-menu">
                    <a href="{{ route('admin.employees.index') }}"><i class="fa fa-users"></i> Employés</a>
                    <a href="{{ route('admin.clients.index') }}"><i class="fa fa-heart"></i> Clients</a>
                    <a href="{{ route('admin.leaves.index') }}"><i class="fa fa-plane"></i> Congés</a>
                    <a href="{{ route('admin.employee-messages.index') }}"><i class="fa fa-envelope"></i> Messages employés</a>
                </div>
            </li>
            <li class="nav-dropdown {{ request()->routeIs('admin.services.*','admin.stocks.*','admin.payments.*','admin.reports.*') ? 'active' : '' }}">
                <button class="nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                    <i class="fa fa-building"></i> Salon <i class="fa fa-angle-down" style="font-size:12px;"></i>
                </button>
                <div class="nav-dropdown-menu">
                    <a href="{{ route('admin.services.index') }}"><i class="fa fa-scissors"></i> Services</a>
                    <a href="{{ route('admin.stocks.index') }}"><i class="fa fa-cubes"></i> Stocks</a>
                    <a href="{{ route('admin.payments.index') }}"><i class="fa fa-credit-card"></i> Paiements</a>
                    <a href="{{ route('admin.reports.index') }}"><i class="fa fa-bar-chart"></i> Rapports</a>
                </div>
            </li>
        </ul>

        <div class="beauty-nav-right">
            {{-- Theme button --}}
            <button class="nav-icon-btn" onclick="openThemePanel()" title="Personnaliser le thème">
                <i class="fa fa-paint-brush"></i>
            </button>

            {{-- User avatar --}}
            <div class="nav-user-wrapper nav-dropdown" style="position:relative;">
                <button class="nav-user-btn" onclick="toggleNavDropdown(this)">
                    @if(auth()->check() && auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Photo" style="width:36px;height:36px;border-radius:10px;object-fit:cover;border:2px solid var(--primary);">
                    @else
                    <div class="user-placeholder">
                        {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'A' }}
                    </div>
                    @endif
                    <span class="user-name">{{ auth()->check() ? auth()->user()->name : '' }}</span>
                    <i class="fa fa-angle-down" style="font-size:12px; color: #8E8E8E;"></i>
                </button>
                <div class="profile-dropdown nav-dropdown-menu" style="right:0;left:auto;">
                    @if(auth()->check())
                    <div class="profile-dropdown-header" style="display:flex;align-items:center;gap:12px;">
                        @if(auth()->user()->photo)
                            <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Photo" style="width:42px;height:42px;border-radius:12px;object-fit:cover;border:2px solid var(--primary);flex-shrink:0;">
                        @else
                            <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--dark));display:flex;align-items:center;justify-content:center;color:white;font-size:16px;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h6>{{ auth()->user()->name }}</h6>
                            <small>{{ auth()->user()->email }}</small>
                        </div>
                    </div>
                    @endif
                    <a href="{{ route('admin.profile.index') }}"><i class="fa fa-user" style="color:var(--primary);width:18px;text-align:center;"></i> Mon Profil</a>
                    <a href="{{ route('admin.dashboard') }}"><i class="fa fa-home" style="color:var(--primary);width:18px;text-align:center;"></i> Tableau de bord</a>
                    <hr style="margin:4px 14px;border-color:var(--primary-soft);">
                    <button class="logout-btn" onclick="event.preventDefault();document.getElementById('nav-logout-form').submit();">
                        <i class="fa fa-sign-out" style="color:#E74C5F;width:18px;text-align:center;"></i> Déconnexion
                    </button>
                </div>
            </div>
            <form id="nav-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>

            <button class="mobile-toggle" onclick="document.getElementById('mobileMenu').classList.toggle('show');">
                <i class="fa fa-bars"></i>
            </button>
        </div>
    </div>
</nav>

{{-- Mobile Menu --}}
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-section">Navigation</div>
    <a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i> Accueil</a>
    <div class="mobile-section">Rendez-vous</div>
    <a href="{{ route('admin.appointments.index') }}"><i class="fa fa-list"></i> Tous les RDV</a>
    <a href="{{ route('admin.appointments.create') }}"><i class="fa fa-plus-circle"></i> Nouveau RDV</a>
    <a href="{{ route('admin.schedules.index') }}"><i class="fa fa-clock-o"></i> Planning</a>
    <div class="mobile-section">Personnes</div>
    <a href="{{ route('admin.employees.index') }}"><i class="fa fa-users"></i> Employés</a>
    <a href="{{ route('admin.clients.index') }}"><i class="fa fa-heart"></i> Clients</a>
    <a href="{{ route('admin.leaves.index') }}"><i class="fa fa-plane"></i> Congés</a>
    <a href="{{ route('admin.employee-messages.index') }}"><i class="fa fa-envelope"></i> Messages</a>
    <div class="mobile-section">Salon</div>
    <a href="{{ route('admin.services.index') }}"><i class="fa fa-scissors"></i> Services</a>
    <a href="{{ route('admin.stocks.index') }}"><i class="fa fa-cubes"></i> Stocks</a>
    <a href="{{ route('admin.payments.index') }}"><i class="fa fa-credit-card"></i> Paiements</a>
    <a href="{{ route('admin.reports.index') }}"><i class="fa fa-bar-chart"></i> Rapports</a>
    <div class="mobile-section">Compte</div>
    <a href="{{ route('admin.profile.index') }}"><i class="fa fa-user"></i> Mon Profil</a>
    <a href="#" onclick="event.preventDefault();openThemePanel();document.getElementById('mobileMenu').classList.remove('show');">
        <i class="fa fa-paint-brush"></i> Personnaliser le thème
    </a>
    <hr style="border-color:var(--primary-soft);">
    <a href="#" onclick="event.preventDefault();document.getElementById('nav-logout-form').submit();" style="color:#E74C5F;">
        <i class="fa fa-sign-out" style="color:#E74C5F;"></i> Déconnexion
    </a>
</div>

{{-- ============== CONTENT ============== --}}
<div class="admin-content">
    @yield('content')
</div>

{{-- ============== FOOTER ============== --}}
<footer class="beauty-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="footer-brand mb-3">KAARJA Beauté</div>
                <p style="font-size:14px;">Votre salon de beauté de confiance. Nous sublimisons votre beauté naturelle avec passion et expertise.</p>
                <div class="mt-3">
                    <a href="#" class="social-link"><i class="fa fa-facebook"></i></a>
                    <a href="#" class="social-link"><i class="fa fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fa fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Liens rapides</h5>
                <ul class="list-unstyled" style="font-size:14px;line-height:2.2;">
                    <li><a href="{{ route('admin.services.index') }}"><i class="fa fa-angle-right mr-2"></i>Nos Services</a></li>
                    <li><a href="{{ route('admin.appointments.create') }}"><i class="fa fa-angle-right mr-2"></i>Nouveau RDV</a></li>
                    <li><a href="{{ route('admin.profile.index') }}"><i class="fa fa-angle-right mr-2"></i>Mon Profil</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Horaires</h5>
                <ul class="list-unstyled" style="font-size:14px;line-height:2.2;">
                    <li><i class="fa fa-clock-o mr-2" style="color:var(--primary-light);"></i>Lun - Sam : 9h - 18h</li>
                    <li><i class="fa fa-clock-o mr-2" style="color:var(--primary-light);"></i>Dimanche : Fermé</li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Contact</h5>
                <ul class="list-unstyled" style="font-size:14px;line-height:2.2;">
                    <li><i class="fa fa-phone mr-2" style="color:var(--primary-light);"></i>01 23 45 67 89</li>
                    <li><i class="fa fa-envelope mr-2" style="color:var(--primary-light);"></i>info@kaarja.com</li>
                </ul>
            </div>
        </div>
        <div class="beauty-footer-bottom">
            &copy; {{ date('Y') }} KAARJA Beauté. Tous droits réservés.
        </div>
    </div>
</footer>

{{-- ============== THEME SETTINGS PANEL ============== --}}

<div class="theme-panel-overlay" id="themePanelOverlay" onclick="closeThemePanel()"></div>

<div class="theme-panel" id="themePanel">
    <div class="theme-panel-header">
        <h4><i class="fa fa-paint-brush mr-2"></i>Personnalisation</h4>
        <p>Choisissez votre style préféré</p>
        <button class="theme-panel-close" onclick="closeThemePanel()"><i class="fa fa-times"></i></button>
    </div>
    <div class="theme-panel-body">

        {{-- Color themes --}}
        <div class="theme-section">
            <div class="theme-section-title"><i class="fa fa-palette"></i> Thème couleur</div>
            <div class="color-themes-grid">
                <div class="color-theme-option" data-theme="rose-gold" onclick="setColorTheme('rose-gold')">
                    <div class="color-theme-dots">
                        <span style="background:#B76E79;"></span><span style="background:#4A1942;"></span><span style="background:#D4AF37;"></span>
                    </div>
                    <span class="color-theme-label">Rose Gold</span>
                </div>
                <div class="color-theme-option" data-theme="ocean-blue" onclick="setColorTheme('ocean-blue')">
                    <div class="color-theme-dots">
                        <span style="background:#2E86AB;"></span><span style="background:#1A3A5C;"></span><span style="background:#F6AE2D;"></span>
                    </div>
                    <span class="color-theme-label">Océan</span>
                </div>
                <div class="color-theme-option" data-theme="emerald" onclick="setColorTheme('emerald')">
                    <div class="color-theme-dots">
                        <span style="background:#2D8B61;"></span><span style="background:#1B4332;"></span><span style="background:#F0A500;"></span>
                    </div>
                    <span class="color-theme-label">Émeraude</span>
                </div>
                <div class="color-theme-option" data-theme="royal-purple" onclick="setColorTheme('royal-purple')">
                    <div class="color-theme-dots">
                        <span style="background:#7C3AED;"></span><span style="background:#3B0764;"></span><span style="background:#F59E0B;"></span>
                    </div>
                    <span class="color-theme-label">Violet Royal</span>
                </div>
                <div class="color-theme-option" data-theme="sunset" onclick="setColorTheme('sunset')">
                    <div class="color-theme-dots">
                        <span style="background:#E85D3A;"></span><span style="background:#7C2D12;"></span><span style="background:#F7B32B;"></span>
                    </div>
                    <span class="color-theme-label">Coucher de soleil</span>
                </div>
                <div class="color-theme-option" data-theme="teal-coral" onclick="setColorTheme('teal-coral')">
                    <div class="color-theme-dots">
                        <span style="background:#0D9488;"></span><span style="background:#134E4A;"></span><span style="background:#F87171;"></span>
                    </div>
                    <span class="color-theme-label">Turquoise</span>
                </div>
                <div class="color-theme-option" data-theme="cherry" onclick="setColorTheme('cherry')">
                    <div class="color-theme-dots">
                        <span style="background:#DB2777;"></span><span style="background:#831843;"></span><span style="background:#FBBF24;"></span>
                    </div>
                    <span class="color-theme-label">Cerise</span>
                </div>
                <div class="color-theme-option" data-theme="slate" onclick="setColorTheme('slate')">
                    <div class="color-theme-dots">
                        <span style="background:#475569;"></span><span style="background:#1E293B;"></span><span style="background:#3B82F6;"></span>
                    </div>
                    <span class="color-theme-label">Classique</span>
                </div>
            </div>
        </div>

        {{-- Dark mode --}}
        <div class="theme-section">
            <div class="theme-section-title"><i class="fa fa-moon-o"></i> Mode d'affichage</div>
            <div class="dark-mode-toggle" onclick="toggleDarkMode()">
                <div class="toggle-info">
                    <div class="toggle-icon"><i class="fa fa-moon-o"></i></div>
                    <div>
                        <div class="toggle-label">Mode sombre</div>
                        <div class="toggle-desc">Réduire la luminosité</div>
                    </div>
                </div>
                <div class="switch-track" id="darkModeSwitch"></div>
            </div>
        </div>

    </div>
</div>

{{-- ============== SCRIPTS ============== --}}
<script src="{{ asset('plugins/common/common.min.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>

<script>
// Nav dropdown
function toggleNavDropdown(btn) {
    var p = btn.closest('.nav-dropdown'), was = p.classList.contains('open');
    document.querySelectorAll('.nav-dropdown').forEach(function(d){ d.classList.remove('open'); });
    if (!was) p.classList.add('open');
}
document.addEventListener('click', function(e){
    if (!e.target.closest('.nav-dropdown')) document.querySelectorAll('.nav-dropdown').forEach(function(d){ d.classList.remove('open'); });
});

// ========== THEME SYSTEM ==========
var THEME_SAVE_URL = '{{ route("admin.theme.update") }}';
var CSRF_TOKEN = '{{ csrf_token() }}';

function saveThemeToDB(data) {
    fetch(THEME_SAVE_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify(data)
    });
}

function updateSelectArrows() {
    var primary = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim();
    var hex = primary.replace('#', '%23');
    var svg = "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='" + hex + "' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E\")";
    document.querySelectorAll('select.form-control, .form-select').forEach(function(el) {
        el.style.backgroundImage = svg;
    });
}

function setColorTheme(theme) {
    document.documentElement.setAttribute('data-color-theme', theme);
    updateActiveTheme();
    setTimeout(updateSelectArrows, 50);
    saveThemeToDB({ color_theme: theme });
}

function toggleDarkMode() {
    var isDark = document.body.classList.contains('dark-theme');
    if (isDark) {
        document.body.classList.remove('dark-theme');
        document.documentElement.classList.remove('dark-theme');
        saveThemeToDB({ dark_mode: false });
    } else {
        document.body.classList.add('dark-theme');
        document.documentElement.classList.add('dark-theme');
        saveThemeToDB({ dark_mode: true });
    }
    updateDarkSwitch();
}

function updateActiveTheme() {
    var current = document.documentElement.getAttribute('data-color-theme') || 'rose-gold';
    document.querySelectorAll('.color-theme-option').forEach(function(el){
        el.classList.toggle('active', el.dataset.theme === current);
    });
}

function updateDarkSwitch() {
    var sw = document.getElementById('darkModeSwitch');
    if (sw) sw.classList.toggle('on', document.body.classList.contains('dark-theme'));
}

function openThemePanel() {
    document.getElementById('themePanel').classList.add('open');
    document.getElementById('themePanelOverlay').classList.add('open');
    updateActiveTheme();
    updateDarkSwitch();
}
function closeThemePanel() {
    document.getElementById('themePanel').classList.remove('open');
    document.getElementById('themePanelOverlay').classList.remove('open');
}

// Init
(function(){
    @if($globalDarkMode ?? false)
        document.body.classList.add('dark-theme'); document.documentElement.classList.add('dark-theme');
    @endif
    @if(($globalColorTheme ?? 'rose-gold') !== 'rose-gold')
        document.documentElement.setAttribute('data-color-theme', '{{ $globalColorTheme }}');
    @endif
    setTimeout(function(){ updateActiveTheme(); updateDarkSwitch(); updateSelectArrows(); }, 100);
})();

// Close mobile menu on link click
document.querySelectorAll('#mobileMenu a').forEach(function(a){
    a.addEventListener('click', function(){ document.getElementById('mobileMenu').classList.remove('show'); });
});
</script>

@include('partials.delete-confirm-modal')
<script>
(function(){
    let modalEl=document.getElementById('deleteConfirmModal');
    if(!modalEl)return; let targetForm=null;
    document.querySelectorAll('form.confirm-delete').forEach(function(f){
        f.addEventListener('submit',function(e){ e.preventDefault(); targetForm=f;
            let m=f.dataset.confirmMessage||f.dataset.message||"Êtes-vous sûr de vouloir supprimer cet élément ?";
            document.getElementById('deleteConfirmModalBody').textContent=m;
            $('#deleteConfirmModal').modal('show');
        });
    });
    document.getElementById('deleteConfirmModalConfirm').addEventListener('click',function(){
        if(!targetForm)return; $('#deleteConfirmModal').modal('hide'); targetForm.submit();
    });
})();
</script>

@include('partials.toggle-confirm-modal')
<script>
(function(){
    let modalEl=document.getElementById('toggleConfirmModal');
    if(!modalEl)return; let targetForm=null;
    document.querySelectorAll('form.confirm-toggle').forEach(function(f){
        f.addEventListener('submit',function(e){ e.preventDefault(); targetForm=f;
            let m=f.dataset.confirmMessage||f.dataset.message||"Êtes-vous sûr de vouloir modifier cet élément ?";
            document.getElementById('toggleConfirmModalBody').textContent=m;
            $('#toggleConfirmModal').modal('show');
        });
    });
    document.getElementById('toggleConfirmModalConfirm').addEventListener('click',function(){
        if(!targetForm)return; $('#toggleConfirmModal').modal('hide'); targetForm.submit();
    });
})();
</script>
@stack('scripts')
</body>
</html>
