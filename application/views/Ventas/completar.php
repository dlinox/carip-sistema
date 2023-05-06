<?=form_open(base_url() . 'ventas/completar_guardar/' . $persona->pers_id . '/' . $venta->id_alumno, ['id' => 'form-2']);?>
    <fieldset>
        <legend>Datos para completar</legend>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">Departamento</label>
                    <?= form_dropdown('departamento', $departamentos, $persona->pers_iddepartamentos, array('class' => 'form-control')); ?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="serie">Dirección</label>
                    <?= form_input(array('name' => 'direccion', 'class' => 'form-control', 'type' => 'text', 'value' => $persona->pers_direccion)) ?>
                </div>
            </div>
            
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="serie">Centro laboral</label>
                    <?= form_input(array('name' => 'centro_laboral', 'class' => 'form-control', 'type' => 'text', 'value' => $persona->pers_centro_laboral)) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="serie">Correo personal</label>
                    <?= form_input(array('name' => 'correo_electronico', 'class' => 'form-control', 'type' => 'text', 'value' => $persona->pers_correo_electronico)) ?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="serie">Fecha de nacimiento</label>
                    <?= form_input(array('name' => 'fecha_nacimiento', 'class' => 'form-control', 'type' => 'date', 'value' => $persona->pers_fecha_nacimiento)) ?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="serie">¿Hubo bienvenida?</label>
                    <?= form_dropdown('hubo_bienvenida', $opciones_bienvenida, $venta->alum_hubo_bienvenida, array('class' => 'form-control')); ?>
                </div>
            </div>
        </div>

        <?php
        if($venta->tipo_pago_id == 1)
        {
        ?>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="serie">Entidad bancaria</label>
                    <?= form_dropdown('entidad_bancaria', $entidades_bancarias, $venta->alum_enba_id, array('class' => 'form-control')); ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="serie">Tipo de tarjeta</label>
                    <?= form_dropdown('tipo_tarjeta', $tipos_tarjeta, $venta->alum_tipo_tarjeta, array('class' => 'form-control')); ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="serie">Franquicia</label>
                    <?= form_dropdown('franquicia', $franquicias, $venta->alum_fran_id, array('class' => 'form-control')); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="form-group">
                    <label for="serie">N° de tarjeta</label>
                    <?= form_input(array('name' => 'numero_tarjeta', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->alum_numero_tarjeta)) ?>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="serie">Mes de caducidad</label>
                    <?= form_input(array('name' => 'mes_caducidad', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->alum_mes_caducidad)) ?>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="serie">Año de caducidad</label>
                    <?= form_input(array('name' => 'anho_caducidad', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->alum_anho_caducidad)) ?>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
    </fieldset>
    <div class="form-group">
        <button type="submit" value="Guardar" class="btn btn-success btn-block btn-sm">
            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar
        </button>
    </div>
<?= form_close() ?>