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
                <form class="ocform " method="POST">
                    <div class="row">
                       <!-- <div class="col-12 col-sm-4 col-md-3">
                            <div class="form-group">
                                <?= form_dropdown('cate_id', [], '', array('class' => 'form-control', 'id' => 'selectCategoria')) ?>
                            </div>
                        </div> -->
                        <div class="col-12 col-sm-4 col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" name="search[value]" id="filtro" placeholder="Buscar" value="">
                            </div>
                        </div>
                    </div>


                </form>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <?php // echo genDataTable('mitabla2', $columns, true, true); 
                    ?>
                    <?php echo genDataTable('mitabla', $columns, ($_user_tipo_id != 5), true); ?>
                </div>
            </div>
        </div>

        <!-- Modal VER DETALLES DE GRUPO -->
        <div class="modal fade" id="modal-ver-detalles" tabindex="-1" aria-labelledby="ver-detalles" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ver-detalles">Detalles del Alumno</h5>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col">
                                <h4>Datos del alumno</h4>
                            </div>
                            <div class="col-sm-6 ">
                                <b>Rubro: </b> <br>
                                ESSALUD
                            </div>
                            <div class="col-sm-3">
                                <b> Tipo Alumno: </b> <br>
                                PROFESIONAL
                            </div>
                            <div class="col-sm-3">
                                <b>Condicion: </b> <br>
                                INDEPENDIENTE
                            </div>

                            <div class="col-sm-6 ">
                                <b>Nombres y Apellidos: </b> <br>
                                ADELAYDA PILAR BRAVO
                            </div>
                            <div class="col-sm-3">
                                <b>DNI: </b> <br>
                                013215448
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h4></h4>
                            </div>
                            <div class="col-sm-6 ">
                                <b>Fecha inscripcion: </b> <br>
                                12/12/2021
                            </div>
                            <div class="col-sm-3">
                                <b> Departamento: </b> <br>
                                Amazonas
                            </div>
                            <div class="col-sm-3">
                                <b>Direccion: </b> <br>
                                Jr. Néstor Cáceres Velásquez
                            </div>

                            <div class="col-sm-3">
                                <b>Centro Laboral: </b> <br>
                                Esalud Arequipa
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" id="btn-imprimir-detalle" data-id class="btn btn-primary">Imprimir</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>