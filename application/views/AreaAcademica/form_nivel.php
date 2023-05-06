<div class="modal-dialog" id="modal-nivel">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Nuevo Nivel</h4>
        </div>
        <form id="fomrNivel" class="form-horizontal" action="<?= base_url() . 'areaAcademica/guardarNivel/' . $nivel->nive_id ?>">
            <input type="hidden" name="grupo_id" id="grupo" value="">
            <div class="modal-body">
                <div id="alerta" class="callout callout-danger error esconder"></div>
                <div class="form-group">
                    <div class="col-sm-8">
                        <label class="control-label">Nombre</label>
                        <input type="text" class="form-control" name="nive_nombre" placeholder="Nombre" value="<?= $nivel->nive_nombre ?>">
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Cantidad de Notas</label>
                        <input type="number" min="1" max="10" class="form-control" name="nive_cantidad_notas" placeholder="Cantidad" value="<?= $nivel->nive_cantidad_notas ?>">
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