<div class="content-wrapper">
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h2 class="box-title"><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> REGISTRO DE PAGO </h2>
            </div>
            <div class="box-body">

                <?= form_open(base_url() . 'Contabilidad/pagopersonaleditar_guardar/' . $pagopersonal->idpago, ['id' => 'form-2']); ?>
                <?= form_input(array('name' => 'idpersona', 'class' => 'form-control', 'type' => 'hidden', 'id' => 'idpersona', 'value' => $usuario->usua_id, 'disabled' => 'disabled')) ?>
                <div class="alert alert-danger error-message-paquete hidden" role="alert"></div>

                <fieldset>
                    <legend>Datos Personales: </legend>
                    <div class="form-group">
                        <div class="col-sm-8 col-md-4">
                            <label class="control-label">Nombre(s) y apellidos:</label>
                            <?= form_input(array('name' => 'nombres', 'class' => 'form-control', 'type' => 'text', 'value' => $usuario->usua_nombres . " " . $usuario->usua_apellidos, 'disabled' => 'disabled')) ?>
                        </div>

                        <div class="col-sm-12 col-md-3 col-lg-2">
                            <label class="control-label">Cargo:</label>
                            <?= form_dropdown('usua_tipo', $tipo_usuarios, $usuario->usua_tipo, array('class' => 'form-control', 'disabled' => 'disabled')) ?>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <label class="control-label">Fecha de Pago:</label>
                            <?= form_input(array('name' => 'fechapago', 'class' => 'form-control', 'type' => 'date', 'value' => $pagopersonal->fecha)) ?>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="control-label text-primary">Mes a Pagar:</label>
                            <?= form_input(array('name' => 'mes', 'id' => 'mes_pagar', 'class' => 'form-control', 'type' => 'month', 'value' => $pagopersonal->anio_mes)) ?>
                        </div>

                </fieldset>

                <?php if ($ultimo_pago != false) :
                    $total = $ultimo_pago->monto +  $ultimo_pago->bono - $ultimo_pago->descuento - $ultimo_pago->adelanto + $ultimo_pago->comisiondirecta +   $ultimo_pago->comisionasesores + ($ultimo_pago->horas * $ultimo_pago->costohora);
                ?>
                    <hr>
                    <div class="alerta-pago">
                        <i class="fas fa-cash-register"></i>
                        ULTIMO PAGO: <b><?= $ultimo_pago->mes ?>, total de S/. <?= $total ?> </b> - PAGADO EL: <b><?= $ultimo_pago->fecha ?> </b>
                    </div>
                    <hr>
                <?php endif; ?>

                <fieldset>
                    <legend>Resumen de Pago</legend>

                    <div class="col-xs-12">
                    <table class="table table-condensed table-responsive table-striped">
                            <thead>
                                <tr>
                                    <td></td>
                                    <th colspan="4">Descripcion</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td colspan="4">Sueldo Neto:</td>
                                    <td><?= form_input(array('name' => 'monto', 'class' => 'form-control', 'type' => 'text', 'onkeyup' => "sum();", 'id' => 'monto', 'value' => $pagopersonal->monto)) ?></td>
                                </tr>

                                <?php if($usuario->usua_tipo == 5): $i = 0; ?>
                                    <?php foreach($cursodictado as  $curso): ?>
                                    <tr class="<?= $usuario->usua_tipo != 5 ? 'hidden' : '' ?>">
                                        <td></td>
                                        <td><label for="curso">Curso Dictado</label>
                                            <?= form_dropdown('cursodictado[]', $cursodictado, $curso, array('class' => 'form-control', 'name' => 'curso','disabled' => 'disabled')) ?>
                                        </td>
                                        <td><label for="horas">Horas Dictadas:</label>
                                            <?= form_input(array('name' => 'horas[]', 'class' => 'form-control horas-cant', 'type' => 'number', 'min' => 0, 'value' => explode(',', $pagopersonal->doc_horas)[$i])) ?></td>
                                        <td><label for="costohora">Costo por Hora:</label>
                                            <?= form_input(array('name' => 'costohora[]', 'class' => 'form-control costo-hora', 'type' => 'number', 'min' => 0,  'value' => explode(',', $pagopersonal->doc_costoshora)[$i])) ?></td>
                                        <td>
                                        <td><label for="horas">-</label>
                                            <?= form_input(array( 'class' => 'form-control curso-total', 'type' => 'text', 'value' => '0.00', 'disabled' => 'disabled')) ?></td>
                                        </td>
                                       
                                    </tr>
                                    <?php 
                                $i++;
                                endforeach; ?>
                                <?php endif; ?>



                                <tr class="<?= ($usuario->usua_tipo != 1 && $usuario->usua_tipo != 7) ? 'hidden' : '' ?>">

                                    <td></td>
                                    <td colspan="4">Comision Directa</td>
                                    <td><?= form_input(array('name' => 'comisiondirecta', 'class' => 'form-control', 'type' => 'text', 'onkeyup' => "sum();", 'id' => 'comisiondirecta[]', 'value' => $pagopersonal->comisiondirecta)) ?></td>
                                </tr>
                                <tr class="<?= $usuario->usua_tipo != 1 ? 'hidden' : '' ?>">
                                    <td></td>
                                    <td colspan="4">Comision por Asesor de Venta</td>
                                    <td><?= form_input(array('name' => 'comisionasesores', 'class' => 'form-control', 'type' => 'text', 'onkeyup' => "sum();", 'id' => 'comisionasesores[]', 'value' => $pagopersonal->comisionasesores)) ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="4">Bonificacion</td>
                                    <td><?= form_input(array('name' => 'bono', 'class' => 'form-control', 'type' => 'text', 'onkeyup' => "sum();", 'id' => 'bono[]', 'value' => $pagopersonal->bono)) ?></td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td colspan="4" class="text-red">Descuentos</td>
                                    <td><?= form_input(array('name' => 'descuento', 'class' => 'form-control text-red', 'type' => 'text', 'onkeyup' => "sum();", 'disabled' => 'disabled', 'id' => 'descuento', 'value' =>  $pagopersonal->descuento)) ?></td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td colspan="4" class="text-red">Adelantos</td>
                                    <td><?= form_input(array('name' => 'adelanto', 'class' => 'form-control text-red', 'type' => 'text', 'onkeyup' => "sum();", 'disabled' => 'disabled', 'id' => 'adelanto', 'value' =>  $pagopersonal->adelanto)) ?></td>
                                </tr>

                                <tr class="info">
                                    <td></td>
                                    <td colspan="4"><strong>TOTAL</strong> </td>
                                    <td>
                                        <strong><?= form_input(array('name' => 'total', 'class' => 'form-control', 'type' => 'text', 'id' => 'total')) ?></strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-md-12">
                            <label class="control-label">Observacion:</label>
                            <td><?= form_input(array('name' => 'observacion', 'class' => 'form-control', 'type' => 'text', 'value' => $pagopersonal->observacion)) ?></td>
                        </div>
                    </div>
                </fieldset>
                <hr>

                <div class="form-group">
                    <button type="submit" value="Guardar" class="btn btn-success btn-block btn-sm">
                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar
                    </button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </section>
</div>