{{--
    Vue : Nouveau message employé
    Description : Formulaire d'envoi d'un nouveau message à l'administration du salon.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-pencil"></i></div>
                <div>
                    <h2 class="beauty-page-title">Nouveau Message</h2>
                    <p class="beauty-page-subtitle">Envoyer un message à l'administration</p>
                </div>
            </div>
            <a href="{{ route('employee.messages.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('employee.messages.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="subject">Sujet <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="subject"
                               class="form-control @error('subject') is-invalid @enderror"
                               value="{{ old('subject') }}" required placeholder="Ex: Demande de changement d'horaires">
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="message">Message <span class="text-danger">*</span></label>
                        <textarea name="message" id="message" rows="8"
                                  class="form-control @error('message') is-invalid @enderror"
                                  required placeholder="Détaillez votre message ici...">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-paper-plane mr-2"></i>Envoyer le message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
