<?php
// echo '<pre>';
// print_r($llamada);
// die();
?>

<div class="content-wrapper">
	<section class="content">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"> <i class="fa fa-chevron-circle-left" onclick="history.back()"></i> REGISTRO DE NUEVA LLAMADA </h3>
				</div>
				<div class="box-body">
					<?= form_open(base_url() . 'llamadas/guardar/' . $llamada->id_llamada) ?>

					<input type="hidden" name="_persona_id" id="_persona_id" value="<?= $llamada->llam_pers_id; ?>" />
					<input type="hidden" name="_producto_id" id="_producto_id" value="<?= $llamada->llam_prod_id; ?>" />
					<div class="alert alert-danger error-message-paquete hidden" role="alert"></div>

					<div class="form-group">

						<div id="persona"></div>

						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Estado</label>
									<?= form_dropdown('respuestas_id', $estado, $llamada->respuestas_id, array('class' => 'form-control', 'id' => 'habilitado')); ?>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="selectCategoria">Categoria</label>
									<?= form_dropdown('cate_id', [], '', array('class' => 'form-control', 'id' => 'selectCategoria')) ?>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Producto</label>
									<select name="prod_id" id="producto"></select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<button type="submit" value="Guardar" class="btn btn-success btn-sm">
									<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar
								</button>
							</div>
							<?php
							if ($llamada->id_llamada) {
							?>
								<div class="col-sm-2">
									<a class="btn btn-success btn-sm" href="<?= base_url() . 'Ventas/crear?id=' . $llamada->id_llamada . '&producto=' . $llamada->llam_prod_id . '&persona=' . $llamada->llam_pers_id; ?>">Venta</a>
								</div>
							<?php
							}
							?>

						</div>
					</div>
					<?= form_close() ?>
				</div>
			</div>
		</div>
	</section>
</div>