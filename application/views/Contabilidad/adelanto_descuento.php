<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <form class="ocform form-inline">
                    <div class="form-group">
                        <label for="rango">Año - Mes: </label>
                        <input class="form-control" type="month" name="fecha" />
                    </div>
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