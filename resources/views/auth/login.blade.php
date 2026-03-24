{{--
    Vue : Page de connexion administrateur
    Description : Formulaire de connexion pour l'administrateur du salon avec email, mot de passe et lien de récupération.
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Connexion - KAARJA Beauté</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        :root, [data-color-theme="rose-gold"] {
            --primary: #B76E79; --primary-light: #D4979F; --primary-soft: #F8E8EE;
            --accent: #D4AF37; --bg: #FFF8F0; --dark: #4A1942; --dark-light: #6B2D5B;
            --rose-gold: var(--primary);
            --rose-gold-light: var(--primary-light);
            --soft-pink: var(--primary-soft);
            --warm-gold: var(--accent);
            --cream: var(--bg);
            --deep-plum: var(--dark);
            --plum-light: var(--dark-light);
        }

        body { margin:0;padding:0;font-family:'Poppins',sans-serif;background:var(--bg);min-height:100vh; }
        h1,h2,h3,h4,h5,h6 { font-family:'Playfair Display',serif; }
        .login-split{ display:flex; min-height:100vh; }
        .login-left{ flex:0 0 45%; background:linear-gradient(135deg,var(--primary) 0%,var(--dark)100%); display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; }
        .left-content{ position:relative; z-index:2; text-align:center; padding:40px; max-width:400px; }
        .login-right{ flex:1; display:flex; align-items:center; justify-content:center; padding:40px 20px; background:var(--bg); position:relative; }
        .form-wrapper{ width:100%; max-width:420px; position:relative; z-index:1; }
        .form-logo{ width:64px;height:64px;margin:0 auto 24px;border-radius:50%; background:linear-gradient(135deg,var(--primary) 0%,var(--dark)100%); display:flex; align-items:center; justify-content:center; box-shadow:0 8px 24px rgba(0,0,0,0.15); }
        .form-wrapper h3{ text-align:center; color:var(--dark); font-size:28px; font-weight:700; margin:0 0 6px; }
        .form-wrapper .form-tagline{ text-align:center; color:var(--primary); font-size:13px; margin-bottom:28px; }
        .alert-luxury{ border-radius:10px; padding:12px 16px; font-size:13px; margin-bottom:16px; position:relative; }
        .form-group-luxury{ margin-bottom:20px; }
        .input-luxury{ width:100%; padding:14px 16px; border:1.5px solid #e8d5d9; border-radius:10px; }
        .form-extras{ display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; }
        .btn-luxury{ display:block; width:100%; padding:14px; border:none; border-radius:10px; background:linear-gradient(135deg,var(--primary) 0%,var(--dark)100%); color:#fff; }
        .divider-luxury{ display:flex; align-items:center; margin:24px 0; }
        .btn-register-luxury{ display:block; width:100%; padding:13px; border:2px solid var(--primary); border-radius:10px; background:transparent; color:var(--primary); }
        .back-link{ display:block; text-align:center; margin-top:24px; color:#999; }
    </style>
</head>

@extends('auth.layout')

@section('title','Connexion Administrateur')

@section('left')
    <div class="left-content">
        <div class="left-icon"><i class="fa fa-shield"></i></div>
        <h2>Portail <span>Administrateur</span></h2>
        <div class="gold-line"></div>
        <p class="left-subtitle">Administration et gestion globale du salon.</p>
        <ul class="left-features">
            <li><i class="fa fa-users"></i> Gestion des utilisateurs</li>
            <li><i class="fa fa-cogs"></i> Paramètres et configurations</li>
            <li><i class="fa fa-line-chart"></i> Statistiques</li>
        </ul>
    </div>
@endsection

@section('right')
    <div class="form-logo"><i class="fa fa-user-secret"></i></div>
    <h3>Connexion Administrateur</h3>
    <p class="form-tagline">Accédez au panneau d'administration</p>

    @if(session('error'))
        <div class="alert-luxury alert-danger" role="alert">
            <i class="fa fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert-luxury alert-danger" role="alert">
            <i class="fa fa-exclamation-circle"></i>
            <div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ url('/login') }}" autocomplete="off">
        @csrf
        <div class="form-group-luxury">
            <label for="email"><i class="fa fa-envelope"></i> Email</label>
            <input type="email" name="email" id="email" class="input-luxury" value="{{ old('email') }}" required autofocus autocomplete="username">
        </div>
        <div class="form-group-luxury">
            <label for="password"><i class="fa fa-lock"></i> Mot de passe</label>
            <input type="password" name="password" id="password" class="input-luxury" required autocomplete="current-password">
        </div>
        <div class="form-extras">
            <label class="custom-check-luxury">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <span>Se souvenir de moi</span>
            </label>
            <a href="{{ route('admin.password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
        </div>
        <button class="btn-luxury" type="submit"><i class="fa fa-sign-in"></i> Se connecter</button>
    </form>

    <div class="divider-luxury"><span>ou</span></div>
    <a href="{{ route('home') }}" class="back-link"><i class="fa fa-arrow-left"></i> Retour</a>
@endsection

</div>

<script src="{{ asset('plugins/common/common.min.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>

<script>
    (function(){
        const savedTheme = localStorage.getItem('salon-theme');
        const savedColor = localStorage.getItem('salon-color-theme');
        if (savedTheme === 'dark') document.documentElement.classList.add('dark-theme');
        if (savedColor) document.documentElement.setAttribute('data-color-theme', savedColor);
        else document.documentElement.setAttribute('data-color-theme','rose-gold');

        window.setColorTheme = function(color){
            document.documentElement.setAttribute('data-color-theme', color);
            localStorage.setItem('salon-color-theme', color);
        };

        window.toggleDarkMode = function(enabled){
            if (enabled) document.documentElement.classList.add('dark-theme');
            else document.documentElement.classList.remove('dark-theme');
            localStorage.setItem('salon-theme', enabled ? 'dark' : 'light');
        };

        const btn = document.getElementById('theme-toggle-btn');
        const panel = document.getElementById('theme-panel');
        const darkToggle = document.getElementById('dark-toggle');
        btn && btn.addEventListener('click', ()=>{ panel.style.display = panel.style.display === 'none' ? 'block' : 'none'; });
        darkToggle && (darkToggle.checked = document.documentElement.classList.contains('dark-theme'));
        darkToggle && darkToggle.addEventListener('change', (e)=> toggleDarkMode(e.target.checked));
    })();
</script>

</body>
</html>
