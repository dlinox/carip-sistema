<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4>Registrar Adelanto o Descuento:</h4>
        </div>
        <form class="form-horizontal" action="<?= base_url() ?>Contabilidad/adelantoDescuentoGuardar/<?= $pago->adde_id ?>" method="POST">
            <input type="hidden" name="usuario" value="<?= $pago->adde_usua_id ?>">
            <div class="modal-body">
                <div class="callout callout-danger error esconder"></div>
                <div class="form-group">
                    <div class="col-sm-5">
                        <label class="control-label">Tipo:</label>
                        <?= form_dropdown('tipo', $tipo, $pago->adde_tipo, array('class' => 'form-control', 'id' => 'tipo')); ?>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Fecha:</label>
                        <input type="month" class="form-control" name="fecha" value="<?= $pago->adde_anio_mes ?>">
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Importe:</label>
                        <input type="text" class="form-control" name="importe" placeholder="Importe" value="<?= $pago->adde_importe ?>" palceholder="Importe">
                    </div>

                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="control-label">Descripcion:</label>
                        <input type="text" class="form-control" name="descripcion" value="<?= $pago->adde_descripcion ?>" palceholder="Observacion">
                    </div>
                </div>
                <!--<div class="form-group">
                    <div class="col-sm-4  pull-right">
                        <label>Tipo de comprobante</label>
                        <?php
                        $tipos_comp = ['A4' => 'A4', 'Ticket' => 'Ticket']
                        ?>
                        <?= form_dropdown('tipo_comp', $tipos_comp, 'A4', array('class' => 'form-control')) ?>
                    </div>
                </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>