<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title"></h4>
        </div>
        <?= form_open(base_url() . 'AtencionCliente/editarSave/' . $alumno->idpagos) ?>
        <div class="modal-body">
            <div class="alert alert-danger error-message hidden" role="alert"></div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Costo del Curso:</label>
                        <?= form_input(array('class' => 'form-control', 'value' => $costo_con_descuento, 'disabled' => 'disabled')) ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Monto</label>
                        <?= form_input(array('name' => 'monto', 'class' => 'form-control', 'value' => $pagado)) ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Fecha Pago</label>
                        <?= form_input(array('name' => 'fechapago', 'class' => 'form-control', 'type' => 'date', 'value' => $fecha)) ?>
                    </div>
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