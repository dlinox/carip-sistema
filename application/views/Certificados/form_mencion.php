<div class="modal-dialog modal-sm">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">x</span></button>
      <h4 class="modal-title">Mencion</h4>
    </div>
    <?= form_open(base_url() . 'certificados/mencion_guardar/' . $mencion->cert_menc_id) ?>
    <div class="modal-body">
      <div class="alert alert-danger error-message hidden" role="alert"></div>
      <div class="form-group">
        <div class="row ">
          <div class="col-sm-12">
            <label>Nombre:</label>
            <?= form_input(array('name' => 'cert_menc_nombre', 'placeholder' => 'Ingrese nombre de la mención', 'class' => 'form-control', 'value' => $mencion->cert_menc_nombre)) ?>
          </div>

          <div class="col-sm-12">
            <label>Descripción:</label>
            <?= form_textarea(array('name' => 'cert_menc_descripcion', 'placeholder' => 'Ingrese una descripción de la mención', 'class' => 'form-control', 'rows' => '2',  'value' => $mencion->cert_menc_descripcion)) ?>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Guardar</button>
      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
    </div>
    <?= form_close() ?>
  </div>
</div>