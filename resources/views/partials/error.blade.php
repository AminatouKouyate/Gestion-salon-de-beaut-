{{--
    Vue : Messages d'erreur (partial)
    Description : Composant d'affichage des erreurs de validation et messages d'erreur de session.
--}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" style="border-radius:14px;border:none;font-size:14px;">
        <i class="fa fa-exclamation-circle mr-2"></i>{{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif
