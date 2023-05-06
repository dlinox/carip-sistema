<?php
$path_foto = base_url()  . "assets/img";
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Editar Logdo</h4>
        </div>


        <form id="fomr-empresa-logo" class="form-horizontal" enctype="multipart/form-data">
            <div class="modal-body">
                <div id="alerta" class="callout callout-danger error esconder"></div>
                <div class="row">
                    <div class="col-sm-12">
                        <label class="control-label mb10">Foto de Usuario</label>
                        <input class="form-control" type="file" name="foto" id="foto-logo" onchange="loadFile(event)">
                        <ul>
                            <li><small>Peso max: 2MB</small></li>
                            <li><small>Formato permitido: jpg|png|jpeg</small></li>
                        </ul>
                    </div>
                    <div class="col-sm-12 mt-2 ">
                        <center>
                            <img style="max-width: 280px;" id="output-logo" width="100%" <?= ($empresa->empr_logo == '' ? "" : "src='$path_foto/$empresa->empr_logo'") ?> />
                        </center>


                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
            </div>
        </form>

    </div>
</div>