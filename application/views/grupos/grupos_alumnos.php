<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <form class="ocform" action="asignar_grupo" method="POST">
                    <div class="alert alert-danger error-message-paquete hidden" role="alert"></div>
                    <div class="row ">
                        <div id="box-curso-periodo">
                            <div class="col-sm-3">
                                <div class="form-group ">
                                    <?= form_dropdown('categoria', [], '', array('class' => 'form-control', 'id' => 'selectCategoria', 'data-placeholder' => 'Categoria')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <select name="id_alumno" class="form-control select2 id_alumno"></select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <input class="btn btn-block btn-success" type="submit" name="asignar_usuario" value="Asignar Grupo">
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