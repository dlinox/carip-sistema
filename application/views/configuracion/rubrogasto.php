<div class="content-wrapper">
  <section class="content-header">
    <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>
    <ol class="add-buttons">
      <li>
        <a class="btn btn-primary btn-flat crear" title="Registrar rubro" type="button" href="<?= base_url() ?>configuracion/rubrogasto_crear">
          <span class="fa fa-plus"></span> Registrar Rubro de Gasto
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
            <input type="text" class="form-control" name="search[value]" id="filtro" placeholder="Buscar" value="">
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