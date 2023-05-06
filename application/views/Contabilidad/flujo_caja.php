<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>
    
    <ol class="add-buttons">
      <li>
        <a class="btn btn-success btn-flat crear" title="Registrar Producto" type="button" href="<?= base_url() ?>Contabilidad/flujo_caja_crear">
          <span class="fa fa-plus"></span> Registrar Gastos
        </a>
      </li>
    </ol>

  </section>

  <section class="content">
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
                <?= form_dropdown('categoria', [], '', array('class' => 'form-control', 'id' => 'selectCategoria', 'data-placeholder' => 'Seleccione una categoria')); ?>
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
                <p class="text-right"><strong>TOTAL DE GASTO : <span class="mone-simb"></span></strong></p>
              </div>
              <div class="col-sm-2">
                <p class="text-right"> <strong> S/.  <span class="egresoflujocaja"></span></strong></p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>