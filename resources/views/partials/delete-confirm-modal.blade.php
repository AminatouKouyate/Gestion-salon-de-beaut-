{{--
    Vue : Modal de confirmation de suppression (partial)
    Description : Composant modal réutilisable pour confirmer la suppression d'un élément avec message personnalisable.
--}}
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border:none;border-radius:20px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
      <div class="modal-body text-center" style="padding:40px 30px 30px;">
        <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#FEE2E2,#FECACA);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
          <i class="fa fa-trash-o" style="font-size:32px;color:#EF4444;"></i>
        </div>
        <h4 style="font-family:'Playfair Display',serif;font-size:22px;font-weight:700;color:#1a1a2e;margin-bottom:10px;">Confirmer la suppression</h4>
        <p id="deleteConfirmModalBody" style="color:#6B7280;font-size:15px;line-height:1.6;margin-bottom:28px;">
          Êtes-vous sûr de vouloir supprimer cet élément ?
        </p>
        <div style="display:flex;gap:12px;justify-content:center;">
          <button type="button" class="btn" data-dismiss="modal" style="padding:12px 28px;border-radius:12px;font-weight:600;font-size:14px;background:#F3F4F6;color:#374151;border:none;min-width:120px;transition:all 0.3s;">
            <i class="fa fa-times mr-2"></i>Annuler
          </button>
          <button type="button" class="btn" id="deleteConfirmModalConfirm" style="padding:12px 28px;border-radius:12px;font-weight:600;font-size:14px;background:linear-gradient(135deg,#EF4444,#DC2626);color:white;border:none;min-width:120px;box-shadow:0 4px 15px rgba(239,68,68,0.3);transition:all 0.3s;">
            <i class="fa fa-trash-o mr-2"></i>Supprimer
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
