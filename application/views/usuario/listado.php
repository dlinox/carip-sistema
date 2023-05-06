<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>
    <ol class="add-buttons">
      <li>
        <a class="btn btn-primary btn-flat crear" title="Registrar usuario" type="button" href="<?= base_url() ?>usuario/crear">
          <span class="fa fa-user-plus"></span> Registrar usuario
        </a>
      </li>
    </ol>

  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">

        <!-- <form class="form-inline" action="asignar_usuario" method="POST">
          <fieldset>
            <div class="alert alert-danger error-message-paquete hidden" role="alert"></div>

            <div class="form-group">
              <?= form_dropdown('lider', $usuarios, '', array('class' => 'form-control', 'id' => 'lider', 'style' => "max-width: 400px")); ?>
            </div>
            <div class="form-group">
              <?= form_dropdown('asesorado', $no_asesorados, '', array('class' => 'form-control', 'id' => 'asesorado', 'style' => "max-width: 400px")); ?>
            </div>
            <div class="form-group">
              <input class="btn btn-success btn-flat" type="submit" name="asignar_usuario" value="Asignar Usuario">
            </div>
          </fieldset>
        </form> -->

        <hr />

        <form class="ocform" method="POST">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label for="filtro">Buscar:</label>
                <input type="text" class="form-control" name="search[value]" id="filtro" placeholder="Buscar" value="">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label for="selectCategoria">Categoria:</label>
                <?= form_dropdown('cate_id', [], '', array('class' => 'form-control', 'id' => 'selectCategoria')) ?>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label for="tipo">Tipo:</label>
                <?= form_dropdown('tipo', $tipos, '', array('class' => 'form-control', 'id' => 'tipo', 'style' => "max-width: 400px")); ?>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <?php echo genDataTable('mitabla', $columns, true, true); ?>
        </div>
      </div>
    </div>
  </section>
</div>