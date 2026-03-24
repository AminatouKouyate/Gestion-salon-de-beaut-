{{--
    Vue : Demande de congé employé
    Description : Formulaire de création d'une demande de congé avec sélection des dates, type de congé et motif.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-plus-circle"></i></div>
                <div>
                    <h2 class="beauty-page-title">Demander un Congé</h2>
                    <p class="beauty-page-subtitle">Soumettre une nouvelle demande de congé</p>
                </div>
            </div>
            <a href="{{ route('employee.leaves.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formulaire de demande de congé</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('employee.leaves.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date" class="form-label">Date de début <span class="text-danger">*</span></label>
                                        <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                               value="{{ old('start_date') }}" min="{{ now()->toDateString() }}" required>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date" class="form-label">Date de fin <span class="text-danger">*</span></label>
                                        <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                               value="{{ old('end_date') }}" min="{{ now()->toDateString() }}" required>
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="reason" class="form-label">Raison de la demande <span class="text-danger">*</span></label>
                                <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror"
                                          rows="4" placeholder="Expliquez brièvement la raison de votre demande de congé..." required>{{ old('reason') }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-paper-plane"></i> Soumettre la demande
                                </button>
                                <a href="{{ route('employee.leaves.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Retour
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('end_date');
    endDateInput.min = startDate;
    if (endDateInput.value && endDateInput.value < startDate) {
        endDateInput.value = startDate;
    }
});
</script>
@endsection
