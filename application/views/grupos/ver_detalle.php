<div class="content-wrapper clearfix">
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> DETALLES DEL GRUPO </h3>
            </div>
            <div class="box-body">
                <fieldset>
                    <legend>Datos del grupo</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Curso</label>
                                <input type="text" class="form-control" value="<?= $resultado->nombre ?>" readonly>
                            </div>
                        </div>

                        <div class=" col-sm-4 col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Fecha de inicio</label>
                                <input type="text" class="form-control" value="<?= $resultado->grup_fechacrea ?>" readonly>
                            </div>
                        </div>

                        <div class=" col-sm-4 col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Periodo</label>
                                <input type="text" class="form-control" value="<?= $resultado->peri_anho . " - " . $resultado->peri_correlativo ?>" readonly>
                            </div>
                        </div>
                        <div class=" col-sm-4 col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Nombre del grupo</label>
                                <input type="text" class="form-control" value="<?= $resultado->grup_nombre . " - " . $resultado->grup_correlativo ?>" readonly>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Días</label>
                                <input type="text" class="form-control" value="<?= $_dias ?>" readonly>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Hora</label>
                                <input type="text" class="form-control" value="<?= $resultado->grup_hora ?>" readonly>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Costo</label>
                                <input type="text" class="form-control" value="<?= $resultado->costo ?>" readonly>
                            </div>
                        </div>

                    </div>
                </fieldset>
                <br>
                <fieldset>
                    <legend>Datos del Docente</legend>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Nombres y Apellidos</label>
                                <input type="text" class="form-control" value="<?= $resultado->usua_nombres . " " . $resultado->usua_nombres ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">DNI</label>
                                <input type="text" class="form-control" value="<?= $resultado->usua_dni ?>" readonly>
                            </div>
                        </div>

                    </div>
                </fieldset>
                <br>

                <hr>

                <fieldset>
                    <legend>Alumnos del grupo</legend>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombres</th>
                                <th scope="col">Apellidos</th>
                                <th scope="col">DNI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alumons["alumnos"] as $alumno) : ?>
                                <tr>
                                    <th scope="row"> <?= $alumno->alum_id ?></th>
                                    <td><?= $alumno->nombres ?></td>
                                    <td><?= $alumno->apellidos ?></td>
                                    <td><?= $alumno->dni ?></td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </fieldset>

                <div class="form-group">
                    <a target="_black" href="<?= base_url() ?>Grupos/imprimirDetalleGrupo/<?= $resultado->grup_id ?>" type="button" value="Guardar" class="btn btn-primary btn-block btn-sm">
                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span> IMPRIMIR
                    </a>
                </div>


            </div>
        </div>
    </section>
</div>