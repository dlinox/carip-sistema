<?php
$url = $es_alumno ? 'AtencionCliente/alumnoEditar/' . $persona->id_alumno
    : 'AtencionCliente/personaEditar/' . $persona->pers_id;
?>
<div class="content-wrapper clearfix">
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> <i class="fa fa-chevron-circle-left" onclick="history.back()"></i> EDITAR DATOS DE ALUMNO</h3>
            </div>
            <div class="box-body">
                <?= form_open(base_url() . $url, ['id' => 'form-pers']); ?>
                <input type="hidden" name="_producto_id" id="_producto_id" value="<?= $persona->productos_id; ?>" />
                <input type="hidden" name="_persona_id" id="_persona_id" value="<?= $persona->alum_pers_id; ?>" />
                <input type="hidden" name="_apoderado_id" id="_apoderado_id" value="<?= $persona->alum_apoderado_id; ?>" />
                <input type="hidden" name="llamada_id" value="<?= isset($_GET['id']) ? $_GET['id'] : 0; ?>" />
                <div class="alert alert-danger error-message-paquete hidden" role="alert"></div>
                <fieldset>
                    <legend>Datos del alumno</legend>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="serie">Nombres</label>
                                <?= form_input(array('name' => 'per_nombre', 'class' => 'form-control', 'type' => 'text', 'value' => $persona->pers_nombres)) ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="serie">Apellidos</label>
                                <?= form_input(array('name' => 'per_apellido', 'class' => 'form-control', 'type' => 'text', 'value' => $persona->pers_apellidos)) ?>

                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="serie">DNI</label>
                                <?= form_input(array('name' => 'per_dni', 'class' => 'form-control', 'type' => 'text', 'value' => $persona->pers_dni)) ?>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="serie">Celular</label>
                                <?= form_input(array('name' => 'pers_celular', 'class' => 'form-control', 'type' => 'text', 'value' => $persona->pers_celular)) ?>
                            </div>
                        </div>

                        <?php if ($es_alumno) : ?>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="serie">Rubro</label>
                                    <?= form_dropdown('rubros_id', $rubros, $persona->rubros_id, array('class' => 'form-control')) ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="serie">Tipo Alumno</label>
                                    <?= form_dropdown('tipo_alumnos_id', $tipo_alumnos, $persona->tipo_alumnos_id, array('class' => 'form-control')) ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="serie">Condicion</label>
                                    <?= form_dropdown('condiciones_id', $condiciones, $persona->condiciones_id, array('class' => 'form-control', 'id' => 'condiciones_id')) ?>
                                </div>
                            </div>

                        <?php endif; ?>
                </fieldset>

                <div id="apoderado">
                    <fieldset>
                        <legend>Datos del apoderado</legend>
                    </fieldset>
                    <div id="s2-apoderado"></div>
                </div>

                <!-- <fieldset>
                    <legend>Datos complementarios</legend>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="serie">Fecha inscripcion</label>
                                <?= form_input(array('name' => 'fecha_inscripcion', 'class' => 'form-control', 'type' => 'date', 'value' => $persona->fecha_inscripcion)) ?>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label class="control-label">Estado</label>
                                <?= form_dropdown('habilitado', $estado, $persona->habilitado, array('class' => 'form-control', 'id' => 'habilitado')); ?>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label for="serie">Observacion</label>
                                <?= form_input(array('name' => 'observacion', 'class' => 'form-control', 'type' => 'text', 'value' => $persona->observacion)) ?>
                            </div>
                        </div>
                    </div>
                </fieldset> -->

                <div class="form-group">
                    <button type="submit" value="Guardar" class="btn btn-success btn-block btn-sm">
                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar
                    </button>
                </div>
                <?= form_close() ?>

                <?php
                if ($show_completar) {
                    //$this->load->view('Ventas/editar');
                    $this->load->view('Ventas/completar');
                }
                ?>
            </div>
        </div>
    </section>
</div>