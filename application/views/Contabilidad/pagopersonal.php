<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>
    <ol class="add-buttons">
      <li>
        <a class="btn btn-danger btn-flat"  type="button" href="<?= base_url() ?>Contabilidad/adelantoDescuento">
        <i class="fas fa-credit-card"></i>  Adelantos y Descuentos
        </a>
      </li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <form class="ocform form-inline">
          <div class="form-group">
            <?= form_dropdown('tipo', $tipos, '', array('class' => 'form-control', 'id' => 'tipo')); ?>
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

      </div>

    </div>
  </section>

</div>