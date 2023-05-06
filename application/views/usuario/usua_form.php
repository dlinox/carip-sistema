<?php
$path_foto = base_url()  . "uploads/FotoUsuario";
?>
<div class="modal-dialog" id="modal-usuario">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
      <h4 class="modal-title"><?= $usua->usua_id != '' ? 'Editar Usuario' : 'Nuevo Usuario' ?></h4>
    </div>
    <form id="fomrUsuario" class="form-horizontal" enctype="multipart/form-data">
      <input type="hidden" name="usua_id" id="usua_id" value="<?= $usua->usua_id ?>">
      <div class="modal-body">
        <div id="alerta" class="callout callout-danger error esconder"></div>
        <div class="form-group">
          <div class="col-sm-6">
            <label class="control-label">Nombre(s)</label>
            <input type="text" class="form-control" name="nombres" placeholder="Nombre(s)" value="<?= $usua->usua_nombres ?>">
          </div>
          <div class="col-sm-6">
            <label class="control-label">Apellidos</label>
            <input type="text" class="form-control" name="apellidos" placeholder="Apellidos" value="<?= $usua->usua_apellidos ?>">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-4">
            <label class="control-label">DNI</label>
            <input type="text" class="form-control" name="dni" value="<?= $usua->usua_dni ?>" placeholder="DNI">
          </div>
          <div class="col-sm-4">
            <label class="control-label">Email</label>
            <input type="text" class="form-control" name="email" value="<?= $usua->usua_email ?>" placeholder="Email">
          </div>
          <div class="col-sm-4">
            <label class="control-label">Teléfono</label>
            <input type="text" class="form-control" name="movil" value="<?= $usua->usua_movil ?>" placeholder="Teléfono">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-6">
            <label class="control-label">Usuario</label>
            <input type="text" name="user" id="user" class="form-control" placeholder="Nombre de usuario" value="<?= $usua->usua_user ?>" <?= ($usua->usua_id != "" ? "disabled" : "") ?>>
          </div>
          <div class="col-sm-6">
            <label class="control-label">Contraseña</label>
            <div class="input-group">
              <span class="input-group-addon">
                <input type="checkbox" id="change_pass" name="change_pass" <?= ($usua->usua_id != "" ? "" : "disabled checked") ?>>
              </span>
              <input type="password" name="pass" placeholder="***********" class="form-control" <?= ($usua->usua_id != "" ? "disabled" : "") ?>>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-4">
            <label for="serie">Categorias</label>
            <?= form_dropdown('cate_id', $categorias, $usua->cate_id, array('class' => 'form-control', 'id' => 'selectCategoria')) ?>
          </div>
          <div class="col-sm-4">
            <label class="control-label">Tipo</label>
            <?= form_dropdown('tipo', $tipo, $usua->usua_tipo, array('class' => 'form-control', 'id' => 'tipo')); ?>
          </div>
          <div class="col-sm-4">
            <label class="control-label">Estado</label>
            <?= form_dropdown('habilitado', $estado, $usua->usua_habilitado, array('class' => 'form-control', 'id' => 'habilitado')); ?>
          </div>


        </div>
        <div class="form-group">
          <div class="col-sm-8">
            <label class="control-label mb10">Foto de Usuario</label>
            <input class="form-control" type="file" name="foto" id="foto" onchange="loadFile(event)">

            <ul>
              <li><small>Tamaño max: 1024px x 1024px</small></li>
              <li><small>Peso max: 2MB</small></li>
              <li><small>Formato permitido: jpg|png|jpeg</small></li>
            </ul>
          </div>
          <div class="col-sm-4 mt-2 ">
            <center>
              <img style="max-width: 280px;" id="output" width="100%" <?= ($usua->usua_foto == '' ? "" : "src='$path_foto/$usua->usua_foto'") ?> />
            </center>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
        <button type="button" onclick="guardarUsuario()" class="btn btn-primary" id="btnGuardar">Guardar</button>
      </div>
    </form>

  </div>
</div>