<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <form class="ocform form-inline">
          <div class="form-group">
            <label for="rango">Desde / Hasta: </label>
            <input class="form-control" type="text" id="rango" name="rango" />
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="search[value]" id="filtro" placeholder="Buscar" value="">
          </div>

          <div class="form-group">
            <?= form_dropdown('categoira', [], '', array('class' => 'form-control', 'id' => 'selectCategoria')); ?>
          </div>
        </form>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <?php echo genDataTable('mitabla', $columns, true, true); ?>
        </div>
        <div class="row">
          <div class="col-sm-7">
          </div>
          <div class="col-sm-5">
            <div class="row">
              <div class="col-sm-7">
                <p class="text-right"><strong>EGRESO GENERADO: <span class="mone-simb"></span></strong></p>
              </div>
              <div class="col-sm-5">
                <p class="text-right"><strong>S/. <span class="egresopagopersonal"></span></strong></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>