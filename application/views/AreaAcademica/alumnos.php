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
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="persona"> Buscar alumno: </label>
                                <?= form_dropdown('persona', [], '', array('class' => 'form-control', 'id' => 'persona', 'data-placeholder' => 'Ingrese DNI o Nombre del estudiante')); ?>
                            </div>
                        </div>

                        <div id="box-curso-periodo">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <?= form_dropdown('categoria', [], '', array('class' => 'form-control', 'id' => 'selectCategoria', 'data-placeholder' => 'Categoria')); ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="box-body">
                <div class="table-responsive">

                    <?php echo genDataTable('mitabla', $columns, ($_user_tipo_id != 5), true); ?>
                </div>
            </div>
        </div>
    </section>
</div>