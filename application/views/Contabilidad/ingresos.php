<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>

  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <form class="ocform">
          <div class="row">
            <div class="col-6 col-sm-4 col-lg-3">
              <div class="form-group">
                <label for="rango">Desde / Hasta: </label>
                <input class="form-control" type="text" id="rango" name="rango" />
              </div>
            </div>
            <div class="col-6 col-sm-4 col-lg-3">
              <div class="form-group">
                <label for="rango">Categoria: </label>
                <?= form_dropdown('categoria', [], '', array('class' => 'form-control', 'id' => 'selectCategoria', 'data-placeholder' => 'Categoria')); ?>
              </div>

            </div>
            <div class="col-6 col-sm-4 col-lg-3">
              <div class="form-group">
                <label for="rango">Buscar: </label>
                <input type="text" class="form-control" name="search[value]" id="filtro" placeholder="Buscar" value="">
              </div>
            </div>
            <div class="col-sm-4 col-lg-3">
            <label class="">  Reporte de Ingresos </label>
            <br>
              <button class="btn btn-danger btn-flat btn-block" id="btn-select" type="button">
                REPORTE PDF
              </button>
            </div>
          </div>

        </form>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <?php echo genDataTable('mitabla', $columns, true, true); ?>
        </div>
        <div class="row" style="margin-top:10px">
          <div class="col-sm-6">
          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="col-sm-10">
                <p class="text-right"><strong>INGRESO GENERADO: <span class="mone-simb"></span></strong></p>
              </div>
              <div class="col-sm-2">
                <p class="text-right"><strong>S/. <span class="ingreso"></span></strong></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>