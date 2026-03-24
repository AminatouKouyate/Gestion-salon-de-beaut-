{{--
    Vue : Layout principal - Espace Client
    Description : Template de base pour toutes les pages de l'espace client : en-tête HTML, polices, thèmes de couleurs, barre de navigation avec notifications, menu latéral responsive et scripts globaux.
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KAARJA Beauté - Espace Client</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"></noscript>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    {{-- NE PAS charger style.css ici — c'est le CSS admin/sidebar qui casse le layout client --}}

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

    /* Content */
    .admin-content { min-height: calc(100vh - 72px - 80px); padding: 30px 0; }
    .admin-content .container-fluid { max-width: 1400px; margin: 0 auto; padding: 0 24px; }
    .content-body { margin-left: 0 !important; }
    .nk-sidebar, .nav-header, .quixnav, .deznav { display: none !important; }
    .content-body .container-fluid { max-width: 1400px; margin: 0 auto; padding: 0 24px; }

    /* Page titles */
    .page-titles { padding: 8px 0 24px; margin-bottom: 0 !important; border: none; }
    .page-titles .welcome-text h4 {
        font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700;
        color: var(--dark); margin: 0;
    }
    .page-titles .welcome-text p { color: #8E8E8E; font-size: 14px; margin: 4px 0 0; }
    .content-body h1 { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: var(--dark); }
    .content-body h2, .content-body h3, .content-body h5 { font-family: 'Playfair Display', serif; color: var(--dark); }

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

    .card.gradient-1, .card.gradient-2, .card.gradient-3, .card.gradient-4 { border-radius: 18px !important; }
    .card.gradient-1 { background: linear-gradient(135deg, var(--primary), var(--dark)) !important; }
    .card.gradient-2 { background: linear-gradient(135deg, var(--primary-light), var(--primary)) !important; }
    .card.gradient-3 { background: linear-gradient(135deg, var(--dark-light), var(--dark)) !important; }
    .card.gradient-4 { background: linear-gradient(135deg, var(--accent), var(--primary)) !important; }

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

    /* Forms */
    .form-control, .form-select {
        border: 2px solid rgba(0,0,0,0.08) !important; border-radius: 12px !important;
        padding: 10px 16px; font-size: 14px; transition: all 0.3s; background: white;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary) !important; box-shadow: 0 0 0 3px var(--primary-soft) !important; outline: none;
    }
    .form-label, label:not(.btn):not(.form-check-label) { font-weight: 600; font-size: 14px; color: var(--dark); margin-bottom: 6px; }
    select.form-control, .form-select {
        appearance: none; -webkit-appearance: none; -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23B76E79' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
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

    /* Badges */
    .badge { padding: 5px 12px; border-radius: 8px; font-weight: 600; font-size: 12px; letter-spacing: 0.3px; }
    .badge-primary, .bg-primary:not(.card) { background: var(--primary-soft) !important; color: var(--primary) !important; }
    .badge-success, .bg-success:not(.card) { background: #d1fae5 !important; color: #059669 !important; }
    .badge-danger, .bg-danger:not(.card) { background: #fee2e2 !important; color: #dc2626 !important; }
    .badge-warning, .bg-warning:not(.card) { background: #fef3c7 !important; color: #92400e !important; }
    .badge-info, .bg-info:not(.card) { background: var(--primary-soft) !important; color: var(--dark) !important; }
    .badge-secondary, .bg-secondary:not(.card) { background: var(--primary-soft) !important; color: var(--dark) !important; }

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
    .modal-body { padding: 24px; }
    .modal-footer { border-top: 1px solid var(--primary-soft) !important; padding: 16px 24px; }

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

    /* List group */
    .list-group-item { border-radius: 12px !important; border: 1px solid rgba(0,0,0,0.06) !important; margin-bottom: 4px; }

    /* ===================== BEAUTY COMPONENTS ===================== */
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

    .beauty-stat {
        background: white; border-radius: 18px; padding: 24px;
        display: flex; align-items: center; gap: 16px;
        border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.3s;
    }
    .beauty-stat:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
    .beauty-stat-icon {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;
    }
    .beauty-stat-icon.rose { background: var(--primary-soft); color: var(--primary); }
    .beauty-stat-icon.green { background: #d1fae5; color: #059669; }
    .beauty-stat-icon.gold { background: #fef3c7; color: #d97706; }
    .beauty-stat-icon.plum { background: #f0e5f5; color: var(--dark); }
    .beauty-stat h3 { font-family: 'Playfair Display', serif; font-size: 24px; margin: 0; color: var(--dark); }
    .beauty-stat p { font-size: 13px; color: #8E8E8E; margin: 2px 0 0; }

    .beauty-card {
        background: white; border-radius: 18px; overflow: hidden;
        border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
    .beauty-card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 24px; border-bottom: 1px solid rgba(0,0,0,0.06);
    }
    .beauty-card-header h4 { font-family: 'Playfair Display', serif; font-size: 18px; margin: 0; color: var(--dark); }
    .beauty-card-body { padding: 20px 24px; }
    .beauty-link { font-size: 13px; font-weight: 600; color: var(--primary); text-decoration: none; }
    .beauty-link:hover { color: var(--dark); text-decoration: none; }

    .beauty-empty { text-align: center; padding: 40px 20px; }
    .beauty-empty i { font-size: 48px; color: var(--primary-light); opacity: 0.4; display: block; margin-bottom: 16px; }
    .beauty-empty h5 { font-family: 'Playfair Display', serif; color: var(--dark); margin-bottom: 8px; }
    .beauty-empty p { color: #8E8E8E; font-size: 15px; }

    /* FullCalendar */
    .fc .fc-button-primary { background: var(--primary) !important; border-color: var(--primary) !important; border-radius: 8px !important; }
    .fc .fc-button-primary:hover { background: var(--dark) !important; }
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

    /* ===================== DARK MODE ===================== */
    .dark-theme { background: #1a1a2e; color: #E8E8E8; }
    .dark-theme body { background: #1a1a2e; color: #E8E8E8; }
    .dark-theme .beauty-navbar { background: rgba(26,26,46,0.92); border-bottom-color: rgba(255,255,255,0.06); }
    .dark-theme .nav-dropdown-menu, .dark-theme .profile-dropdown, .dark-theme .notif-dropdown { background: #252540; }
    .dark-theme .nav-dropdown-menu a:hover, .dark-theme .profile-dropdown a:hover { background: #2a2040; }
    .dark-theme .notif-item { border-bottom-color: #333; }
    .dark-theme .notif-item:hover { background: #2a2040; }
    .dark-theme .nav-user-btn { background: #2a2040; }
    .dark-theme .nav-user-btn .user-name { color: #E8E8E8; }
    .dark-theme .card:not(.gradient-1):not(.gradient-2):not(.gradient-3):not(.gradient-4) { background: #252540 !important; }
    .dark-theme .card-header { background: #2a2555 !important; border-bottom-color: #333355 !important; }
    .dark-theme .card-header .card-title, .dark-theme .card-header h4, .dark-theme .card-header h5 { color: #E8E8E8 !important; }
    .dark-theme .card-body { color: #E8E8E8; }
    .dark-theme .card-footer { background: #252540 !important; border-top-color: #333355 !important; }
    .dark-theme .table thead th { background: #2a2555 !important; color: #E8E8E8; }
    .dark-theme .table tbody td { color: #ccc; border-bottom-color: #333355 !important; }
    .dark-theme .table tbody tr:hover { background: #2a2040 !important; }
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
    .dark-theme .page-titles .welcome-text h4, .dark-theme .content-body h1,
    .dark-theme .content-body h2, .dark-theme .content-body h3, .dark-theme .content-body h5 { color: #E8E8E8 !important; }
    .dark-theme label:not(.btn):not(.form-check-label) { color: #E8E8E8 !important; }
    .dark-theme .text-muted { color: #9E9E9E !important; }
    .dark-theme .list-group-item { background: #2a2040 !important; border-color: #333355 !important; color: #E8E8E8; }
    .dark-theme .beauty-stat, .dark-theme .beauty-card { background: #252540; border-color: #333355; }
    .dark-theme .beauty-stat h3, .dark-theme .beauty-card-header h4,
    .dark-theme .beauty-page-title { color: #E8E8E8 !important; }
    .dark-theme .beauty-card-header { border-bottom-color: #333355; }
    .dark-theme .beauty-nav > li > a, .dark-theme .beauty-nav > li > .nav-dropdown-toggle { color: #E8E8E8; }
    .dark-theme .nav-dropdown-menu a { color: #E8E8E8; }
    .dark-theme .profile-dropdown a, .dark-theme .profile-dropdown button { color: #E8E8E8; }
    .dark-theme .profile-dropdown-header h6 { color: #E8E8E8; }
    .dark-theme .mobile-menu { background: rgba(26,26,46,0.98); }
    .dark-theme .mobile-menu a { color: #E8E8E8; }
    .dark-theme .mobile-menu a:hover { background: #2a2040; }
    .dark-theme .fc .fc-toolbar-title { color: #E8E8E8; }
    .dark-theme select.form-control { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%239E9E9E' d='M6 8L1 3h10z'/%3E%3C/svg%3E") !important; }

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

    /* ===================== RESPONSIVE ===================== */
    @media (max-width: 767px) {
        body { padding-top: 72px; }
        .admin-content { padding: 16px 0; }
        .admin-content .container-fluid, .content-body .container-fluid { padding: 0 14px; }
        .card { border-radius: 14px !important; }
        .card-body { padding: 16px; }
        .card-header { padding: 14px 16px !important; }
        .table thead th { font-size: 11px; padding: 10px 8px; }
        .table tbody td { padding: 10px 8px; font-size: 13px; }
        .table-responsive { margin: 0 -16px; padding: 0 16px; }
        .btn { padding: 8px 14px; font-size: 13px; border-radius: 10px; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .form-control, .form-select { padding: 8px 14px; font-size: 16px; min-height: 40px; }
        .modal-content { border-radius: 14px !important; margin: 10px; }
        .modal-body { padding: 16px; }
        .alert { padding: 12px 16px; font-size: 13px; border-radius: 12px; }
        .pagination { flex-wrap: wrap; }
        .page-item .page-link { padding: 6px 10px; font-size: 13px; }
        h1, .content-body h1 { font-size: 20px !important; }
        h2, .content-body h2 { font-size: 18px !important; }
        .row > [class*="col-lg"], .row > [class*="col-md"] { margin-bottom: 16px; }
        .notif-dropdown { width: calc(100vw - 20px) !important; right: -60px !important; }
    }
    @media (max-width: 480px) {
        .admin-content .container-fluid, .content-body .container-fluid { padding: 0 10px; }
        .beauty-card { border-radius: 14px; }
        .beauty-card-header { padding: 12px 14px; flex-wrap: wrap; gap: 8px; }
        .beauty-card-header h4 { font-size: 15px; }
        .beauty-card-body { padding: 12px 14px; }
        .btn-group { display: flex; flex-wrap: wrap; gap: 4px; }
        .btn-group .btn { border-radius: 8px !important; }
        .form-control, .form-select { font-size: 16px; }
        .table { font-size: 12px; }
        .table thead th { white-space: nowrap; }
        .modal-dialog { margin: 8px; }
    }

    </style>
    @stack('styles')
</head>

<body>

{{-- ============== NAVBAR ============== --}}
<nav class="beauty-navbar">
    <div class="navbar-inner">
        <a href="{{ route('client.dashboard') }}" class="beauty-brand">
            <img src="{{ asset('images/image1.jpg') }}" alt="Logo">
            <span class="beauty-brand-name">KAARJA Beauté</span>
            <span style="font-size:11px;padding:3px 10px;border-radius:20px;background:linear-gradient(135deg,#10b981,#059669);color:white;margin-left:8px;font-family:'Poppins',sans-serif;">Client</span>
        </a>

        <ul class="beauty-nav">
            <li class="{{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                <a href="{{ route('client.dashboard') }}"><i class="fa fa-home"></i> Accueil</a>
            </li>
            <li class="nav-dropdown {{ request()->routeIs('client.appointments.*') ? 'active' : '' }}">
                <button class="nav-dropdown-toggle" onclick="toggleNavDropdown(this)">
                    <i class="fa fa-calendar"></i> Rendez-vous <i class="fa fa-angle-down" style="font-size:12px;"></i>
                </button>
                <div class="nav-dropdown-menu">
                    <a href="{{ route('client.appointments.index') }}"><i class="fa fa-list"></i> Mes Rendez-vous</a>
                    <a href="{{ route('client.appointments.create') }}"><i class="fa fa-plus-circle"></i> Prendre RDV</a>
                    <a href="{{ route('client.appointments.calendar') }}"><i class="fa fa-calendar"></i> Calendrier</a>
                    <a href="{{ route('client.appointments.history') }}"><i class="fa fa-history"></i> Historique</a>
                </div>
            </li>
            <li class="{{ request()->routeIs('client.services*') ? 'active' : '' }}">
                <a href="{{ route('client.services') }}"><i class="fa fa-scissors"></i> Services</a>
            </li>
            <li class="{{ request()->routeIs('client.payments.*') ? 'active' : '' }}">
                <a href="{{ route('client.payments.index') }}"><i class="fa fa-credit-card"></i> Paiements</a>
            </li>
            {{-- Chatbot accessible via bulle flottante, retiré de la navbar --}}
        </ul>

        <div class="beauty-nav-right">
            {{-- Notifications --}}
            <div class="nav-dropdown" style="position:relative;">
                <button class="nav-icon-btn" onclick="toggleNavDropdown(this)" title="Notifications">
                    <i class="fa fa-bell"></i>
                    @php
                        $unreadCount = auth('clients')->check() ? auth('clients')->user()->unreadNotifications->count() : 0;
                    @endphp
                    @if($unreadCount > 0)
                        <span class="notif-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                </button>
                <div class="notif-dropdown nav-dropdown-menu" style="right:0;left:auto;padding:0;width:340px;">
                    <div class="notif-dropdown-header">
                        <i class="fa fa-bell mr-2"></i>Notifications
                    </div>
                    <div class="notif-dropdown-body">
                        @if(auth('clients')->check())
                            @forelse(auth('clients')->user()->unreadNotifications->take(5) as $notif)
                                <a href="{{ route('client.notifications.index') }}" class="notif-item">
                                    <h6>{{ $notif->data['title'] ?? 'Notification' }}</h6>
                                    <p>{{ Str::limit($notif->data['message'] ?? '', 60) }}</p>
                                    <small>{{ $notif->created_at->diffForHumans() }}</small>
                                </a>
                            @empty
                                <div class="text-center py-4" style="color:#8E8E8E;font-size:13px;">
                                    <i class="fa fa-bell-slash-o mb-2" style="font-size:24px;display:block;opacity:0.3;"></i>
                                    Aucune notification
                                </div>
                            @endforelse
                        @endif
                    </div>
                    <div class="notif-footer">
                        <a href="{{ route('client.notifications.index') }}">Voir toutes les notifications</a>
                    </div>
                </div>
            </div>

            {{-- User avatar --}}
            <div class="nav-user-wrapper nav-dropdown" style="position:relative;">
                <button class="nav-user-btn" onclick="toggleNavDropdown(this)">
                    @if(auth('clients')->check() && auth('clients')->user()->photo)
                        <img src="{{ asset('storage/' . auth('clients')->user()->photo) }}" alt="Photo">
                    @else
                    <div class="user-placeholder">
                        {{ auth('clients')->check() ? strtoupper(substr(auth('clients')->user()->name, 0, 1)) : 'C' }}
                    </div>
                    @endif
                    <span class="user-name">{{ auth('clients')->check() ? auth('clients')->user()->name : '' }}</span>
                    <i class="fa fa-angle-down" style="font-size:12px; color: #8E8E8E;"></i>
                </button>
                <div class="profile-dropdown nav-dropdown-menu" style="right:0;left:auto;">
                    @if(auth('clients')->check())
                    <div class="profile-dropdown-header" style="display:flex;align-items:center;gap:12px;">
                        @if(auth('clients')->user()->photo)
                            <img src="{{ asset('storage/' . auth('clients')->user()->photo) }}" alt="Photo" style="width:42px;height:42px;border-radius:12px;object-fit:cover;border:2px solid var(--primary);flex-shrink:0;">
                        @else
                            <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--dark));display:flex;align-items:center;justify-content:center;color:white;font-size:16px;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr(auth('clients')->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h6>{{ auth('clients')->user()->name }}</h6>
                            <small>{{ auth('clients')->user()->email }}</small>
                        </div>
                    </div>
                    @endif
                    <a href="{{ route('client.profile') }}"><i class="fa fa-user" style="color:var(--primary);width:18px;text-align:center;"></i> Mon Profil</a>
                    <a href="{{ route('client.dashboard') }}"><i class="fa fa-home" style="color:var(--primary);width:18px;text-align:center;"></i> Tableau de bord</a>
                    <a href="{{ route('client.appointments.create') }}"><i class="fa fa-calendar-plus-o" style="color:var(--primary);width:18px;text-align:center;"></i> Prendre RDV</a>
                    <hr style="margin:4px 14px;border-color:var(--primary-soft);">
                    <button class="logout-btn" onclick="event.preventDefault();document.getElementById('nav-logout-form').submit();">
                        <i class="fa fa-sign-out" style="color:#E74C5F;width:18px;text-align:center;"></i> Déconnexion
                    </button>
                </div>
            </div>
            <form id="nav-logout-form" action="{{ route('client.logout') }}" method="POST" style="display:none;">@csrf</form>

            <button class="mobile-toggle" onclick="document.getElementById('mobileMenu').classList.toggle('show');">
                <i class="fa fa-bars"></i>
            </button>
        </div>
    </div>
</nav>

{{-- Mobile Menu --}}
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-section">Navigation</div>
    <a href="{{ route('client.dashboard') }}"><i class="fa fa-home"></i> Accueil</a>
    <div class="mobile-section">Rendez-vous</div>
    <a href="{{ route('client.appointments.index') }}"><i class="fa fa-list"></i> Mes Rendez-vous</a>
    <a href="{{ route('client.appointments.create') }}"><i class="fa fa-plus-circle"></i> Prendre RDV</a>
    <a href="{{ route('client.appointments.calendar') }}"><i class="fa fa-calendar"></i> Calendrier</a>
    <a href="{{ route('client.appointments.history') }}"><i class="fa fa-history"></i> Historique</a>
    <div class="mobile-section">Salon</div>
    <a href="{{ route('client.services') }}"><i class="fa fa-scissors"></i> Nos Services</a>
    <a href="{{ route('client.payments.index') }}"><i class="fa fa-credit-card"></i> Paiements</a>
    {{-- Chatbot accessible via bulle flottante --}}
    <div class="mobile-section">Compte</div>
    <a href="{{ route('client.profile') }}"><i class="fa fa-user"></i> Mon Profil</a>
    <a href="{{ route('client.notifications.index') }}"><i class="fa fa-bell"></i> Notifications
        @if($unreadCount > 0)<span class="badge badge-danger ml-2">{{ $unreadCount }}</span>@endif
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
                    <li><a href="{{ route('client.services') }}"><i class="fa fa-angle-right mr-2"></i>Nos Services</a></li>
                    <li><a href="{{ route('client.appointments.create') }}"><i class="fa fa-angle-right mr-2"></i>Prendre RDV</a></li>
                    <li><a href="{{ route('client.profile') }}"><i class="fa fa-angle-right mr-2"></i>Mon Profil</a></li>
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
                    <li><i class="fa fa-phone mr-2" style="color:var(--primary-light);"></i>+223 XX XX XX XX</li>
                    <li><i class="fa fa-envelope mr-2" style="color:var(--primary-light);"></i>info@kaarja.com</li>
                </ul>
            </div>
        </div>
        <div class="beauty-footer-bottom">
            &copy; {{ date('Y') }} KAARJA Beauté. Tous droits réservés.
        </div>
    </div>
</footer>

{{-- ============== CHATBOT FLOTTANT STYLE WHATSAPP ============== --}}
@auth('clients')
<div id="chatbot-widget">
    {{-- Bulle flottante --}}
    <button id="chatbot-toggle" onclick="toggleChatbot()" title="Assistant IA">
        <i class="fa fa-comments" id="chatbot-icon-open"></i>
        <i class="fa fa-times" id="chatbot-icon-close" style="display:none;"></i>
        <span id="chatbot-unread-badge" style="display:none;">1</span>
    </button>

    {{-- Fenêtre de chat --}}
    <div id="chatbot-window">
        {{-- Header --}}
        <div id="chatbot-header">
            <div class="chatbot-header-info">
                <div class="chatbot-avatar">
                    <i class="fa fa-robot"></i>
                    <span class="chatbot-online-dot"></span>
                </div>
                <div>
                    <div class="chatbot-name">KAARJA Assistant</div>
                    <div class="chatbot-status">En ligne</div>
                </div>
            </div>
            <div class="chatbot-header-actions">
                <button onclick="toggleChatbot()" title="Fermer"><i class="fa fa-times"></i></button>
            </div>
        </div>

        {{-- Zone de messages --}}
        <div id="chatbot-messages">
            <div class="chatbot-date-separator">
                <span>Aujourd'hui</span>
            </div>
            <div class="chatbot-msg chatbot-msg-bot">
                <div class="chatbot-msg-bubble">
                    Bonjour {{ auth('clients')->user()->name ?? '' }} !<br>
                    Comment puis-je vous aider ?
                </div>
                <div class="chatbot-msg-time">{{ now()->format('H:i') }}</div>
            </div>
            <div class="chatbot-suggestions">
                <button class="chatbot-suggestion-btn" data-msg="Voir les services">Services</button>
                <button class="chatbot-suggestion-btn" data-msg="Voir les promotions">Promos</button>
                <button class="chatbot-suggestion-btn" data-msg="Prendre rendez-vous">Rendez-vous</button>
                <button class="chatbot-suggestion-btn" data-msg="Mes points fidélité">⭐ Fidélité</button>
            </div>
        </div>

        {{-- Zone de saisie --}}
        <div id="chatbot-input-area">
            <form id="chatbot-form">
                @csrf
                <div class="chatbot-input-wrap">
                    <input type="text" id="chatbot-input" placeholder="Écrivez un message..." autocomplete="off">
                    <button type="submit" id="chatbot-send-btn">
                        <i class="fa fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* ============ BULLE FLOTTANTE ============ */
#chatbot-toggle {
    position: fixed; bottom: 24px; right: 24px; z-index: 9999;
    width: 60px; height: 60px; border-radius: 50%; border: none;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; font-size: 26px;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.25);
    transition: all 0.3s; animation: chatbot-pulse 2.5s ease-in-out infinite;
}
#chatbot-toggle:hover { transform: scale(1.1); box-shadow: 0 6px 28px rgba(0,0,0,0.3); }
#chatbot-toggle.active { animation: none; background: var(--dark); }
#chatbot-unread-badge {
    position: absolute; top: -4px; right: -4px;
    background: #E74C5F; color: white; font-size: 11px; font-weight: 700;
    min-width: 20px; height: 20px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid white;
}
@keyframes chatbot-pulse {
    0%, 100% { box-shadow: 0 4px 20px rgba(0,0,0,0.25); }
    50% { box-shadow: 0 4px 30px rgba(0,0,0,0.4); }
}

/* ============ FENÊTRE DE CHAT ============ */
#chatbot-window {
    position: fixed; bottom: 100px; right: 24px; z-index: 9998;
    width: 380px; height: 520px; border-radius: 18px; overflow: hidden;
    background: var(--bg); display: none; flex-direction: column;
    box-shadow: 0 8px 40px rgba(0,0,0,0.18);
    animation: chatbot-slide-up 0.3s ease;
}
#chatbot-window.open { display: flex; }
@keyframes chatbot-slide-up {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ============ HEADER ============ */
#chatbot-header {
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; padding: 14px 18px;
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0;
}
.chatbot-header-info { display: flex; align-items: center; gap: 12px; }
.chatbot-avatar {
    width: 42px; height: 42px; border-radius: 50%;
    background: rgba(255,255,255,0.2); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; position: relative;
}
.chatbot-online-dot {
    position: absolute; bottom: 1px; right: 1px;
    width: 10px; height: 10px; border-radius: 50%;
    background: var(--accent); border: 2px solid var(--dark);
}
.chatbot-name { font-family: 'Playfair Display', serif; font-weight: 600; font-size: 16px; }
.chatbot-status { font-size: 12px; opacity: 0.85; }
.chatbot-header-actions button {
    background: rgba(255,255,255,0.15); border: none; color: white;
    width: 32px; height: 32px; border-radius: 8px;
    font-size: 16px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
}
.chatbot-header-actions button:hover { background: rgba(255,255,255,0.3); }

/* ============ MESSAGES ============ */
#chatbot-messages {
    flex: 1; overflow-y: auto; padding: 14px 16px;
    background: var(--bg);
    display: flex; flex-direction: column;
}
.chatbot-date-separator {
    text-align: center; margin: 8px 0 14px;
}
.chatbot-date-separator span {
    background: var(--primary-soft); color: var(--dark-light); font-size: 11px;
    padding: 4px 14px; border-radius: 10px; font-weight: 500;
}
.chatbot-msg { margin-bottom: 6px; max-width: 85%; animation: chatbot-msg-in 0.25s ease; }
.chatbot-msg-bot { align-self: flex-start; }
.chatbot-msg-user { align-self: flex-end; }
@keyframes chatbot-msg-in {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}
.chatbot-msg-bubble {
    padding: 10px 14px; border-radius: 14px; font-size: 13.5px;
    line-height: 1.5; word-wrap: break-word; white-space: pre-wrap;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.chatbot-msg-bot .chatbot-msg-bubble {
    background: white; color: #2D2D2D;
    border-top-left-radius: 4px;
    border-left: 3px solid var(--primary);
}
.chatbot-msg-user .chatbot-msg-bubble {
    background: var(--primary-soft); color: #2D2D2D;
    border-top-right-radius: 4px;
}
.chatbot-msg-time {
    font-size: 10px; color: #8E8E8E; margin-top: 3px;
    text-align: right;
}
.chatbot-msg-bot .chatbot-msg-time { text-align: left; padding-left: 3px; }

/* Typing */
.chatbot-typing .chatbot-msg-bubble {
    display: flex; gap: 5px; padding: 14px 18px;
    border-left: 3px solid var(--primary);
}
.chatbot-typing span {
    width: 7px; height: 7px; background: var(--primary-light); border-radius: 50%;
    animation: chatbot-typing-bounce 1.4s ease-in-out infinite;
}
.chatbot-typing span:nth-child(2) { animation-delay: 0.2s; }
.chatbot-typing span:nth-child(3) { animation-delay: 0.4s; }
@keyframes chatbot-typing-bounce {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-5px); }
}

/* Suggestions */
.chatbot-suggestions {
    display: flex; flex-wrap: wrap; gap: 6px;
    margin: 8px 0; padding: 0 4px;
}
.chatbot-suggestion-btn {
    background: white; border: 1.5px solid var(--primary-light); color: var(--dark);
    padding: 7px 16px; border-radius: 20px; font-size: 12px; font-weight: 500;
    cursor: pointer; transition: all 0.25s; white-space: nowrap;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.chatbot-suggestion-btn:hover {
    background: var(--primary); color: white; border-color: var(--primary);
    transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,0.12);
}

/* ============ INPUT ============ */
#chatbot-input-area {
    background: white; padding: 10px 14px; flex-shrink: 0;
    border-top: 1px solid var(--primary-soft);
}
.chatbot-input-wrap {
    display: flex; align-items: center; gap: 10px;
}
#chatbot-input {
    flex: 1; border: 1.5px solid var(--primary-soft); background: var(--bg);
    border-radius: 24px; padding: 10px 18px; font-size: 14px;
    font-family: 'Poppins', sans-serif; outline: none;
    transition: border-color 0.2s;
}
#chatbot-input:focus { border-color: var(--primary-light); }
#chatbot-input::placeholder { color: #8E8E8E; }
#chatbot-send-btn {
    width: 42px; height: 42px; border-radius: 50%; border: none;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; font-size: 16px;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: all 0.25s; flex-shrink: 0;
}
#chatbot-send-btn:hover { transform: scale(1.08); box-shadow: 0 3px 12px rgba(0,0,0,0.2); }

/* ============ RESPONSIVE ============ */
@media (max-width: 480px) {
    #chatbot-window {
        width: calc(100vw - 16px); height: calc(100vh - 80px);
        bottom: 8px; right: 8px; left: 8px; border-radius: 14px;
    }
    #chatbot-toggle { bottom: 16px; right: 16px; width: 54px; height: 54px; font-size: 22px; }
}

/* ============ DARK MODE ============ */
.dark-theme #chatbot-window { background: #1a1a2e; }
.dark-theme #chatbot-messages { background-color: #1a1a2e; }
.dark-theme .chatbot-msg-bot .chatbot-msg-bubble { background: #2a2a3e; color: #E9EDEF; }
.dark-theme .chatbot-msg-user .chatbot-msg-bubble { background: rgba(var(--primary), 0.15); color: #E9EDEF; }
.dark-theme #chatbot-input-area { background: #222236; border-color: #333; }
.dark-theme #chatbot-input { background: #2a2a3e; color: #E9EDEF; border-color: #444; }
.dark-theme .chatbot-suggestion-btn { background: #2a2a3e; color: var(--primary-light); border-color: var(--primary-light); }
.dark-theme .chatbot-suggestion-btn:hover { background: var(--primary); color: white; }
.dark-theme .chatbot-date-separator span { background: #2a2a3e; color: #8E8E8E; }
</style>

<script>
(function(){
    var chatOpen = false;
    var chatInitialized = false;

    window.toggleChatbot = function() {
        var win = document.getElementById('chatbot-window');
        var toggleBtn = document.getElementById('chatbot-toggle');
        var iconOpen = document.getElementById('chatbot-icon-open');
        var iconClose = document.getElementById('chatbot-icon-close');
        var badge = document.getElementById('chatbot-unread-badge');

        chatOpen = !chatOpen;

        if (chatOpen) {
            win.classList.add('open');
            toggleBtn.classList.add('active');
            iconOpen.style.display = 'none';
            iconClose.style.display = 'inline';
            badge.style.display = 'none';
            document.getElementById('chatbot-input').focus();
            scrollChatbot();
        } else {
            win.classList.remove('open');
            toggleBtn.classList.remove('active');
            iconOpen.style.display = 'inline';
            iconClose.style.display = 'none';
        }
    };

    function scrollChatbot() {
        var el = document.getElementById('chatbot-messages');
        el.scrollTop = el.scrollHeight;
    }

    function addBotMessage(text) {
        var msgs = document.getElementById('chatbot-messages');
        var div = document.createElement('div');
        div.className = 'chatbot-msg chatbot-msg-bot';
        div.innerHTML = '<div class="chatbot-msg-bubble">' + text + '</div>'
            + '<div class="chatbot-msg-time">' + new Date().toLocaleTimeString('fr-FR', {hour:'2-digit',minute:'2-digit'}) + '</div>';
        msgs.appendChild(div);
        scrollChatbot();
    }

    function addUserMessage(text) {
        var msgs = document.getElementById('chatbot-messages');
        var div = document.createElement('div');
        div.className = 'chatbot-msg chatbot-msg-user';
        div.innerHTML = '<div class="chatbot-msg-bubble">' + escapeHtml(text) + '</div>'
            + '<div class="chatbot-msg-time">' + new Date().toLocaleTimeString('fr-FR', {hour:'2-digit',minute:'2-digit'}) + '</div>';
        msgs.appendChild(div);
        scrollChatbot();
    }

    function addSuggestions(suggestions) {
        if (!suggestions || !suggestions.length) return;
        var msgs = document.getElementById('chatbot-messages');
        var div = document.createElement('div');
        div.className = 'chatbot-suggestions';
        suggestions.forEach(function(s) {
            var btn = document.createElement('button');
            btn.className = 'chatbot-suggestion-btn';
            btn.textContent = s;
            btn.dataset.msg = s;
            div.appendChild(btn);
        });
        msgs.appendChild(div);
        scrollChatbot();
    }

    function addActions(actions) {
        if (!actions || !actions.length) return;
        var msgs = document.getElementById('chatbot-messages');
        var div = document.createElement('div');
        div.className = 'chatbot-suggestions';
        actions.forEach(function(a) {
            var btn = document.createElement('a');
            btn.href = a.url;
            btn.className = 'chatbot-suggestion-btn';
            btn.textContent = a.label;
            btn.style.textDecoration = 'none';
            div.appendChild(btn);
        });
        msgs.appendChild(div);
        scrollChatbot();
    }

    function showTyping() {
        var msgs = document.getElementById('chatbot-messages');
        var div = document.createElement('div');
        div.className = 'chatbot-msg chatbot-msg-bot chatbot-typing';
        div.id = 'chatbot-typing';
        div.innerHTML = '<div class="chatbot-msg-bubble"><span></span><span></span><span></span></div>';
        msgs.appendChild(div);
        scrollChatbot();
    }

    function hideTyping() {
        var el = document.getElementById('chatbot-typing');
        if (el) el.remove();
    }

    function formatBotText(text) {
        return text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" style="color:#25D366;text-decoration:underline;">$1</a>')
            .replace(/\n/g, '<br>');
    }

    function escapeHtml(text) {
        var d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    async function sendChatbotMessage(message) {
        if (!message.trim()) return;
        addUserMessage(message);
        document.getElementById('chatbot-input').value = '';
        showTyping();

        try {
            var response = await fetch('{{ route("client.chatbot.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            });
            var data = await response.json();
            hideTyping();
            addBotMessage(formatBotText(data.reply));
            if (data.suggestions) addSuggestions(data.suggestions);
            if (data.actions) addActions(data.actions);
        } catch (error) {
            hideTyping();
            addBotMessage('Désolé, une erreur est survenue. Réessayez.');
        }
    }

    // Formulaire
    document.getElementById('chatbot-form').addEventListener('submit', function(e) {
        e.preventDefault();
        sendChatbotMessage(document.getElementById('chatbot-input').value);
    });

    // Suggestions
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('chatbot-suggestion-btn') && e.target.dataset.msg) {
            e.preventDefault();
            sendChatbotMessage(e.target.dataset.msg);
        }
    });
})();
</script>
@endauth



{{-- ============== SCRIPTS ============== --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>

<script>
function toggleNavDropdown(btn) {
    var p = btn.closest('.nav-dropdown'), was = p.classList.contains('open');
    document.querySelectorAll('.nav-dropdown').forEach(function(d){ d.classList.remove('open'); });
    if (!was) p.classList.add('open');
}
document.addEventListener('click', function(e){
    if (!e.target.closest('.nav-dropdown')) document.querySelectorAll('.nav-dropdown').forEach(function(d){ d.classList.remove('open'); });
});

(function(){
    @if($globalDarkMode ?? false)
        document.body.classList.add('dark-theme'); document.documentElement.classList.add('dark-theme');
    @endif
    @if(($globalColorTheme ?? 'rose-gold') !== 'rose-gold')
        document.documentElement.setAttribute('data-color-theme', '{{ $globalColorTheme }}');
    @endif
})();

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
