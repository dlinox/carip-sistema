<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Default Modal</h4>
        </div>
        <?= form_open(base_url() . 'configuracion/producto_guardar/' . $producto->id) ?>
        <div class="modal-body">
            <div class="alert alert-danger error-message hidden" role="alert"></div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-8">
                        <label>Nombre:</label>
                        <?= form_input(array('name' => 'nombre', 'placeholder' => 'Ingrese nombre del curso', 'class' => 'form-control', 'value' => $producto->nombre)) ?>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Sesiones:</label>
                            <?= form_input(array('name' => 'sesiones', 'placeholder' => 'Número de sesiones', 'class' => 'form-control', 'value' => $producto->sesiones)) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Costo de Matricula:</label>
                        <?= form_input(array('name' => 'matricula', 'placeholder' => 'Costo de Matricula', 'class' => 'form-control', 'value' => $producto->matricula)) ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Cuotas:</label>
                        <?= form_input(array('name' => 'cuotas', 'placeholder' => 'Número de Cuotas',  'class' => 'form-control', 'value' => $producto->cuotas)) ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Costo:</label>
                        <?= form_input(array('name' => 'costo', 'placeholder' => 'Costo del curso ', 'class' => 'form-control', 'value' => $producto->costo)) ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Comisión:</label>
                        <?= form_input(array('name' => 'comision', 'placeholder' => 'Comision por venta', 'class' => 'form-control', 'value' => $producto->comision)) ?>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Comisión del asesor:</label>
                        <?= form_input(array('name' => 'comision_asesor', 'placeholder' => 'Comision por venta del asesor', 'class' => 'form-control', 'value' => $producto->comision_asesor)) ?>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Nota Aprobatoria:</label>
                        <?= form_input(array('name' => 'notaaprobatoria', 'placeholder' => 'Comision por venta del asesor', 'class' => 'form-control', 'value' => $producto->notaaprobatoria)) ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="serie">Categorias</label>
                        <?= form_dropdown('cate_id', $categorias, $producto->cate_id, array('class' => 'form-control')) ?>
                    </div>
                </div>

                <div class="col-sm-6">
                    <label class="control-label">Tipo</label>
                    <?= form_dropdown('libre', $tipo, $producto->libre, array('class' => 'form-control', 'id' => 'habilitado')); ?>

                </div>
            </div>


        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
        </div>
        <?= form_close() ?>
    </div>
</div>
<script>
    $(function() {
        $('#cp2').colorpicker({
            format: "hex",
            colorSelectors: {
                'black': '#000000',
                'white': '#ffffff',
                'red': '#FF0000',
                'default': '#777777',
                'primary': '#337ab7',
                'success': '#5cb85c',
                'info': '#5bc0de',
                'warning': '#f0ad4e',
                'danger': '#d9534f'
            }
        });
    });
</script>