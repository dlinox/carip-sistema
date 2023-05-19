<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>

  </section>
  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="box">
      <div class="box-body">
        <div class="card">
          <?= form_open(base_url() . 'certificados/certificado_guardar/' . $certificado->cert_id) ?>
          <div class="card-body">
            <div class="alert alert-danger error-message hidden" role="alert"></div>
            <div class="form-group">
              <div class="row ">
                <div class="col-12 col-md-12">
                  <label>Categoria:</label>
                  <?= form_dropdown('cert_cate_id', $categorias, $certificado->cert_cate_id, array('class' => 'form-control', 'id' => 'selectCategoria'))   ?>
                </div>
                <div class="col-12 col-md-12">
                  <input type="hidden" id="aux_menc" value="<?= $certificado->cert_menc_id ?>" data-name="<?= $certificado->cert_menc_nombre ?>">
                  <label>Mención</label>
                  <?= form_dropdown('cert_menc_id', [], '', array('class' => 'form-control', 'id' => 'mencion', 'data-placeholder' => '* Mención'))   ?>
                </div>

                <div class="col-12 col-md-12">
                  <label>Emicion</label>
                  <?= form_input(array('name' => 'cert_fecha', 'placeholder' => 'Fecha', 'class' => 'form-control', 'placeholder' => 'Arequipa, Enero de 2023', 'value' => $certificado->cert_fecha)) ?>
                </div>

                <div class="col-12 col-md-12">
                  <input type="hidden" id="aux_alum" value="<?= $certificado->cert_alum_id ?>" data-name="<?= $certificado->cert_alum_nombre ?>">
                  <label>Alumno</label>
                  <?= form_dropdown('cert_alum_id', [], $certificado->cert_alum_id, array('class' => 'form-control', 'id' => 'persona', 'data-placeholder' => '* Alumno'))   ?>
                </div>

                <div class="col-12 col-md-4">
                  <label>Prefijo</label>
                  <?= form_input(array('name' => 'cert_prefix', 'placeholder' => 'AAAA', 'class' => 'form-control', 'value' => $certificado->cert_prefix)) ?>
                </div>
                <div class="col-12 col-md-8">
                  <label>Número</label>
                  <?= form_input(array('name' => 'cert_num', 'placeholder' => '0000001', 'class' => 'form-control', 'value' => $certificado->cert_num)) ?>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer ">
            <button type="submit" class="btn btn-primary ">Guardar</button>
            <a href="/certificados/personal" class="btn btn-default pull-left">Cancelar</a>
          </div>
          <?= form_close() ?>
        </div>
      </div>
    </div>
  </section>
</div>