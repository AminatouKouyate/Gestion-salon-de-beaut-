<!-- Modal Bloquer un créneau -->
<div class="modal fade" id="blockSlotModal" tabindex="-1" role="dialog" aria-labelledby="blockSlotModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.schedules.storeBlock') }}" method="POST">
                @csrf
                
                <div class="modal-header">
                    <h5 class="modal-title" id="blockSlotModalLabel">
                        <i class="fa fa-ban mr-2"></i>Bloquer un créneau
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modal_employee_id">Employé</label>
                        <select class="form-control" id="modal_employee_id" name="employee_id">
                            <option value="">Tous les employés</option>
                            @foreach($employees ?? [] as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Laissez vide pour bloquer tous les employés</small>
                    </div>
                    <div class="form-group">
                        <label for="modal_start_datetime">Date/Heure début <span class="text-danger">*</span></label>
                        <input type="datetime-local" 
                               class="form-control" 
                               id="modal_start_datetime" 
                               name="start_datetime" 
                               required>
                    </div>
                    <div class="form-group">
                        <label for="modal_end_datetime">Date/Heure fin <span class="text-danger">*</span></label>
                        <input type="datetime-local" 
                               class="form-control" 
                               id="modal_end_datetime" 
                               name="end_datetime" 
                               required>
                    </div>
                    <div class="form-group">
                        <label for="modal_reason">Raison (optionnel)</label>
                        <textarea class="form-control" 
                                  id="modal_reason" 
                                  name="reason" 
                                  rows="3" 
                                  placeholder="Ex: Fermeture exceptionnelle, formation, maintenance..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save mr-1"></i>Enregistrer le blocage
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>