<div class="content-wrapper clearfix">
	<section class="content">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"> <i class="fa fa-chevron-circle-left" onclick="history.back()"></i> EDITAR DATOS DEL ALUMNO </h3>
			</div>
			<div class="box-body">
				<?=form_open(base_url() . 'ventas/guardaredicion/' . $personas->alum_pers_id, ['id' => 'form-1']); ?>
				<?php //form_open(base_url() . 'ventas/guardar/' . $venta->id_alumno, ['id' => 'form-1']);?>
				<input type="hidden" name="_producto_id" id="_producto_id" value="<?= $venta->productos_id; ?>" />
				<input type="hidden" name="_persona_id" id="_persona_id" value="<?= $venta->alum_pers_id; ?>" />
				<input type="hidden" name="_apoderado_id" id="_apoderado_id" value="<?= $venta->alum_apoderado_id; ?>" />
				<input type="hidden" name="llamada_id" value="<?= isset($_GET['id']) ? $_GET['id'] : 0; ?>" />

				<div class="alert alert-danger error-message-paquete hidden" role="alert"></div>
				<fieldset>
					<legend>Datos del Alumno</legend>
					<div class="row">
						<!-- <div class="col-sm-1">
							<div class="form-group">
								<label for="serie">ID</label>
								<?= form_input('personas', $personas->alum_pers_id, array('class' => 'form-control')) ?>
							</div>
						</div> -->
						<div class="col-sm-3">
							<div class="form-group">
								<label for="serie">Nombres</label>
								<?= form_input(array('name' => 'nombrepersona', 'class' => 'form-control', 'type' => 'text', 'value' => $personas->pers_nombres)) ?>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label for="serie">Apellidos</label>
								<?= form_input(array('name' => 'apellidopersona', 'class' => 'form-control', 'type' => 'text', 'value' => $personas->pers_apellidos)) ?>

							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label for="serie">DNI</label>
								<?= form_input(array('name' => 'dnipersona', 'class' => 'form-control', 'type' => 'text', 'value' => $personas->pers_dni)) ?>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label for="serie">Celular</label>
								<?= form_input(array('name' => 'celularpersona', 'class' => 'form-control', 'type' => 'text', 'value' => $personas->pers_celular)) ?>
							</div>
						</div>


					</div>

					<div class="hidden" id="s2-persona"></div>

				</fieldset>

				<div id="apoderado">
					<fieldset>
						<legend>Datos del apoderado</legend>
					</fieldset>
					<div id="s2-apoderado"></div>
				</div>



				<div class="form-group">
					<button type="submit" value="Guardar" class="editar btn btn-success btn-block btn-sm">
						<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar Cambios
					</button>
				</div>
				<?= form_close() ?>
			</div>
		</div>
	</section>
</div>