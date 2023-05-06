<input type="hidden" name="_user_tipo_id" id="_user_tipo_id" value="<?= $_user_tipo_id; ?>" />
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <form class="ocform" action="" method="POST">
                    <div class="alert alert-danger error-message-paquete hidden" role="alert"></div>
                    <div class="row">
                        <div class="col-6 col-sm-3">
                            <div class="form-group">
                                <label for="selectCategoria">Categoria:</label>
                                <?= form_dropdown('categoria', [], '', array('class' => 'form-control', 'id' => 'selectCategoria')) ?>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <div class="form-group">
                                <label for="completado">Vendedor: </label>
                                <?= form_dropdown('usua_id_vendor', [], '', array('class' => 'form-control', 'id' => 'selectVendedor')) ?>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">

                            <div class="form-group">
                                <label for="rango">Desde / Hasta: </label>
                                <input class="form-control" type="text" id="rango" name="rango" />
                            </div>

                        </div>

                    </div>
                </form>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <?php echo genDataTable('mitabla', $columns, false, true); ?>
                </div>
            </div>
        </div>
    </section>
</div>