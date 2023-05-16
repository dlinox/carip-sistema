<div class="modal-dialog ">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">x</span></button>
      <h4 class="modal-title">Default Modal</h4>
    </div>
    <?= form_open(base_url() . 'certificados/certificado_guardar/' . $certificado->cert_id) ?>
    <div class="modal-body">
      <div class="alert alert-danger error-message hidden" role="alert"></div>
      <div class="form-group">
        <div class="row ">
          <div class="col-12 col-md-12">
            <label>Categoria:</label>
            <?= form_dropdown('cert_cate_id', $categorias, $certificado->cert_cate_id, array('class' => 'form-control', 'id' => 'selectCategoria'))   ?>
          </div>
          <div class="col-12 col-md-12">
            <label>Mención</label>
            <?= form_dropdown('cert_menc_id', $menciones, $certificado->cert_menc_id, array('class' => 'form-control', 'id' => 'selectMencion'))   ?>
          </div>

          <div class="col-12 col-md-12">
            <label>Emicion</label>
            <?= form_input(array('name' => 'cert_fecha', 'placeholder' => 'Fecha', 'class' => 'form-control', 'placeholder' => 'Arequipa, Enero de 2023', 'value' => $certificado->cert_fecha)) ?>
          </div>

          <!-- <div class="col-12 col-md-12">
            <label>Buscar Alumno</label>
            <?= form_dropdown('cert_alum_id', [] , $certificado->cert_alum_id, array('class' => 'form-control select-alumno', 'id' => 'selectAlumno'))   ?>
          </div> -->

          <!-- <div id="persona"></div> -->

          <div class="col-12 col-md-12">
            <label>Alumno</label>
            <?= form_input(array('name' => 'cert_alum_nombre', 'placeholder' => 'Jose Peres', 'class' => 'form-control', 'value' => $certificado->cert_alum_nombre)) ?>
          </div>

          <div class="col-12 col-md-4">
            <label>Prefijo</label>name
            <?= form_input(array('name' => 'cert_prefix', 'placeholder' => 'AAAA', 'class' => 'form-control', 'value' => $certificado->cert_prefix)) ?>
          </div>

          <div class="col-12 col-md-8">
            <label>Número</label>
            <?= form_input(array('name' => 'cert_num', 'placeholder' => '0000001', 'class' => 'form-control', 'value' => $certificado->cert_num)) ?>
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

<script>
$('#persona').s2persona();

</script>