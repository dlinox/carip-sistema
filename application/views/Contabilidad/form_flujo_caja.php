<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            <h4>Registro de Gastos:</h4>
        </div>
        <form class="form-horizontal" action="<?= base_url() ?>Contabilidad/flujo_caja_guardar/<?= $flujocaja->id_flujocaja ?>" method="POST">
            <div class="modal-body">
                <div class="callout callout-danger error esconder"></div>
                <div class="form-group">
                    <div class="col-sm-5">
                        <label class="control-label">Rubro de Gasto:</label>
                        <?= form_dropdown('tipo', $tipo, $flujocaja->rubrogasto_id_rubrogasto, array('class' => 'form-control', 'id' => 'tipo')); ?>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Fecha:</label>
                        <input type="date" class="form-control" name="fecha" placeholder="Fecha" value="<?= $flujocaja->fecha_flujo != '' ? $flujocaja->fecha_flujo : date("Y-m-d") ?>">
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Importe:</label>
                        <input type="text" class="form-control" name="importe" placeholder="Importe" value="<?= $flujocaja->importe_flujo ?>" palceholder="Importe">
                    </div>

                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="control-label">Descripcion:</label>
                        <textarea class="form-control" name="descripcion" rows="5" placeholder="Ingrese alguna descripcion"><?= trim($flujocaja->descripcion_flujo) ?></textarea>
                    </div>
                    <div class="col-sm-12">
                        <label class="control-label">Observacion:</label>
                        <input type="text" class="form-control" name="observacion" placeholder="Ingrese alguna observacion" value="<?= $flujocaja->observacion_flujo ?>" palceholder="Observacion">
                    </div>
                </div>
                <div class="form-group">

                    <div class="col-sm-8">
                        <label>Categorias</label>
                        <?= form_dropdown('categoria', $categorias, $flujocaja->flca_cate_id , array('class' => 'form-control')) ?>
                    </div>

                    <div class="col-sm-4  pull-right">
                        <label>Tipo de comprobante</label>
                        <?php
                        $tipos_comp = ['A4' => 'A4', 'Ticket' => 'Ticket']
                        ?>
                        <?= form_dropdown('tipo_comp', $tipos_comp, 'A4', array('class' => 'form-control')) ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>