<div class="modal-dialog modal-sm">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
      <h4 class="modal-title">Registrar Persona</h4>
    </div>
    <?= form_open(base_url() . 'certificados/persona_guardar/' . $persona->pers_id) ?>
    <div class="modal-body">
      <div class="alert alert-danger error-message hidden" role="alert"></div>
      <div class="form-group">

        <div class="row ">
          <div class="col-sm-12">
            <label>DNI:</label>
            <?= form_input(array('name' => 'pers_dni', 'placeholder' => '', 'class' => 'form-control', 'value' => $persona->pers_dni)) ?>
          </div>
        </div>
        <div class="row ">
          <div class="col-sm-12">
            <label>Nombres:</label>
            <?= form_input(array('name' => 'pers_nombres', 'placeholder' => '', 'class' => 'form-control', 'value' => $persona->pers_nombres)) ?>
          </div>
        </div>
        <div class="row ">
          <div class="col-sm-12">
            <label>Apellidos:</label>
            <?= form_input(array('name' => 'pers_apellidos', 'placeholder' => '', 'class' => 'form-control', 'value' => $persona->pers_apellidos)) ?>
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