<div class="content-wrapper clearfix">
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> <i class="fa fa-chevron-circle-left" onclick="history.back()"></i> DETALLES DEL ALUMNO </h3>
            </div>
            <div class="box-body">
                <fieldset>
                    <legend>Datos personales
                        <button id="btn-editar-persona" type="button" class="btn btn-info btn-sm btn-flat ">
                            <i class="fas fa-user-edit"></i> </button>
                    </legend>
                    <?= form_open(base_url() . 'persona/editarPersona/' . $persona->pers_id) ?>
                    <div class="alert alert-danger error-message-paquete hidden" role="alert"></div>
                    <div class="row" id="info-persona">
                        <div class="col-md-4">
                            <label for="nombres" class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="nombres" name="pers_nombres" value="<?= $persona->pers_nombres ?>" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Apellidos</label>
                            <input type="text" class="form-control" name="pers_apellidos" value="<?= $persona->pers_apellidos ?>" readonly>
                        </div>

                        <div class="col-xs-6 col-md-2">
                            <label class="form-label">DNI</label>
                            <input type="text" class="form-control" name="pers_dni" value="<?= $persona->pers_dni ?>" readonly>
                        </div>
                        <div class="col-xs-6 col-md-2">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" name="pers_fecha_nacimiento" value="<?= $persona->pers_fecha_nacimiento ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Celular</label>
                            <input type="text" class="form-control" name="pers_celular" value="<?= $persona->pers_celular ?>" readonly>
                        </div>
                        <div class="col-md-5 ">
                            <label class="form-label">Correo Electronico</label>
                            <input type="text" class="form-control" name="pers_correo_electronico" value="<?= $persona->pers_correo_electronico ?>" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Departamento</label>
                            <?= form_dropdown('pers_iddepartamentos', $departamentos, $persona->pers_iddepartamentos, array('class' => 'form-control', 'readonly' => 'readonly')) ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="pers_direccion" value="<?= $persona->pers_direccion ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Centro Laboral</label>
                            <input type="text" class="form-control" name="pers_centro_laboral" value="<?= $persona->pers_centro_laboral ?>" readonly>
                        </div>
                        <div class="col-sm-12 ">
                            <button type="submit" id="btn-guardar-persona" class="btn btn-success btn-flat btn-block no-edit">Guardar</button>
                        </div>
                    </div>
                    <?= form_close() ?>
                </fieldset>
                <br>
                <fieldset>
                    <legend>Datos de curso(s)</legend>
                    <div class="box-body">
                        <div class="table-responsive">
                            <?= genDataTable('mitabla', $columns, true, true); ?>
                        </div>
                    </div>
                </fieldset>
                <div class="form-group">
                    <a target="_black" href="<?= base_url() ?>AreaAcademica/imprimirDetalleAlumno/<?= $persona->pers_id ?>" type="button" value="Guardar" class="btn btn-primary btn-block btn-sm">
                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span> IMPRIMIR
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>