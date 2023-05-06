<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>
    <ol class="add-buttons">
      <li>
        <a class="btn btn-primary btn-flat crear" title="Registrar Producto" type="button" href="<?= base_url() ?>configuracion/producto_crear">
          <span class="fa fa-plus"></span> Registrar Producto
        </a>
      </li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <form class="ocform ">
          <div class="row">
            <div class="col-6 col-sm-4">
              <div class="form-group">
                <label for="selectCategoria">Categoria:</label>
                <?= form_dropdown('cate_id', [], '', array('class' => 'form-control', 'id' => 'selectCategoria')) ?>
              </div>
            </div>
            <div class="col-6 col-sm-4">
              <div class="form-group">
                <label for="search"> Buscar: </label>

                <input type="text" class="form-control" name="search[value]" id="filtro" placeholder="Buscar" value="">
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