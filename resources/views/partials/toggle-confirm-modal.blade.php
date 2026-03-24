{{--
    Vue : Modal de confirmation d'activation/désactivation (partial)
    Description : Composant modal pour confirmer l'activation ou la désactivation d'un élément (client, employé, service).
--}}
<div class="modal fade" id="toggleConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border:none;border-radius:20px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
      <div class="modal-body text-center" style="padding:40px 30px 30px;">
        <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--primary-soft),rgba(255,255,255,0.5));display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
          <i class="fa fa-exchange" style="font-size:32px;color:var(--primary);"></i>
        </div>
        <h4 style="font-family:'Playfair Display',serif;font-size:22px;font-weight:700;color:#1a1a2e;margin-bottom:10px;">Confirmer l'action</h4>
        <p id="toggleConfirmModalBody" style="color:#6B7280;font-size:15px;line-height:1.6;margin-bottom:28px;">
          Êtes-vous sûr de vouloir effectuer cette action ?
        </p>
        <div style="display:flex;gap:12px;justify-content:center;">
          <button type="button" class="btn" data-dismiss="modal" style="padding:12px 28px;border-radius:12px;font-weight:600;font-size:14px;background:#F3F4F6;color:#374151;border:none;min-width:120px;transition:all 0.3s;">
            <i class="fa fa-times mr-2"></i>Annuler
          </button>
          <button type="button" class="btn" id="toggleConfirmModalConfirm" style="padding:12px 28px;border-radius:12px;font-weight:600;font-size:14px;background:linear-gradient(135deg,var(--primary),var(--dark));color:white;border:none;min-width:120px;box-shadow:0 4px 15px rgba(0,0,0,0.15);transition:all 0.3s;">
            <i class="fa fa-check mr-2"></i>Confirmer
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
