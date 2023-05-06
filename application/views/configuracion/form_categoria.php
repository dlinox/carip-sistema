<div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Default Modal</h4>
        </div>
        <?= form_open(base_url() . 'configuracion/categoria_guardar/' . $categoria->cate_id) ?>
        <div class="modal-body">
            <div class="alert alert-danger error-message hidden" role="alert"></div>
            <div class="form-group">
                <div class="row ">
                    <div class="col-sm-12">
                        <label>Nombre:</label> <?= $categoria->cate_id ?>
                        <?= form_input(array('name' => 'cate_nombre', 'placeholder' => 'Ingrese nombre de la categoria', 'class' => 'form-control', 'value' => $categoria->cate_nombre)) ?>
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
