<?php
$path_foto = base_url()  . "assets/img";
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>

        <ol class="add-buttons">
            <li>
                <button id="btn-editar-empresa" type="button" class="btn btn-info btn-flat  pull-right">Editar</button>
            </li>
        </ol>
    </section>
    <section class="content">
        <div class="box" id="box-info-empresa">
            <div class="box-header with-border">
                <fieldset>
                    <legend>Datos de la empresa</legend>
                </fieldset>
                <div class="row ">
                    <div class="col-sm-5">
                        <div class="card ">
                            <div class="img-container">
                                <img style="width:100%; max-width:220px;" id="logo-empresa" <?= ($empresa->empr_logo == '' ? "" : "src='$path_foto/$empresa->empr_logo'") ?> alt="Logo de la empresa">

                                <a class="btn btn-info btn-sm btn-flat btn-editar-logo" title="Editar Foto" type="button" href="<?= base_url() ?>empresa/logo">
                                    <i class="fa fa-pen"></i> Editar
                                </a>
                            </div>
                        </div>
                        <button type="button" class="btn btn-flat " onclick="recargarPagina() ">
                            Recargar
                        </button>
                    </div>
                    <div class="col-sm-7">
                        <?= form_open(base_url() . 'empresa/guardar/' . $empresa->empr_id) ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-danger error-message-paquete hidden" role="alert"></div>
                                <div class="form-group">
                                    <label>Nombre / Razon social</label>
                                    <?= form_input(array('name' => 'empr_nombre', 'class' => 'form-control', 'readonly' => 'readonly', 'value' => $empresa->empr_nombre)) ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>RUC</label>
                                    <?= form_input(array('name' => 'empr_ruc', 'class' => 'form-control', 'readonly' => 'readonly', 'value' => $empresa->empr_ruc)) ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Ubicación</label>
                                    <?= form_input(array('name' => 'empr_ubicacion', 'class' => 'form-control', 'readonly' => 'readonly', 'value' => $empresa->empr_ubicacion)) ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Dirección</label>
                                    <?= form_input(array('name' => 'empr_direccion', 'class' => 'form-control', 'readonly' => 'readonly', 'value' => $empresa->empr_direccion)) ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Correo electronico</label>
                                    <?= form_input(array('name' => 'empr_correo', 'class' => 'form-control', 'readonly' => 'readonly', 'value' => $empresa->empr_correo)) ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Teléfono / Celular</label>
                                    <?= form_input(array('name' => 'empr_numero', 'class' => 'form-control', 'readonly' => 'readonly', 'value' => $empresa->empr_numero)) ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Página web / Red Social </label>
                                    <?= form_input(array('name' => 'empr_social', 'class' => 'form-control', 'readonly' => 'readonly', 'value' => $empresa->empr_social)) ?>
                                </div>
                            </div>

                            <div class="col-sm-12 ">
                                <button type="submit" id="btn-guardar-empresa" class="btn btn-success btn-flat btn-block no-edit">Guardar</button>
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    function recargarPagina() {
        window.location.href = window.location.href
        window.location.replace(window.location.href)
    }
</script>