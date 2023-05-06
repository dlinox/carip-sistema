<div class="content-wrapper clearfix">
	<section class="content">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"> <i class="fa fa-chevron-circle-left" onclick="history.back()"></i> REGISTRO DE NUEVO ALUMNO </h3>
			</div>
			<div class="box-body">
				<?= form_open(base_url() . 'ventas/guardar/' . $venta->id_alumno, ['id' => 'form-1']); ?>
				<input type="hidden" name="_producto_id" id="_producto_id" value="<?= $venta->productos_id; ?>" />
				<input type="hidden" name="_persona_id" id="_persona_id" value="<?= $venta->alum_pers_id; ?>" />
				<input type="hidden" name="_apoderado_id" id="_apoderado_id" value="<?= $venta->alum_apoderado_id; ?>" />
				<input type="hidden" name="llamada_id" value="<?= isset($_GET['id']) ? $_GET['id'] : 0; ?>" />

				<div class="alert alert-danger error-message-paquete hidden" role="alert"></div>

				<fieldset>
					<legend>Datos del producto</legend>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label>Categoria</label>
								<br>
								<?= form_dropdown('cate_id', [] , '', array('class' => 'form-control', 'id' => 'selectCategoria')) ?>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label>Producto</label>
								<?= form_dropdown('productos_id', $productos, $venta->productos_id, array('class' => 'form-control', 'id' => 'nombreproducto')) ?>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label>Cantidad de cuotas</label>
								<?= form_input(array('name' => 'cuotas', 'id' => 'numcuotas', 'class' => 'form-control', 'type' => 'text', 'disabled' => 'disabled', 'value' => $venta->cuotas)) ?>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label>Costo de cuota</label>
								<?= form_input(array('name' => 'costo', 'class' => 'form-control', 'type' => 'text', 'disabled' => 'disabled', 'value' => $venta->costo)) ?>
							</div>
						</div>
					</div>
				</fieldset>

				<fieldset>
					<legend>Datos del alumno</legend>

					<div id="s2-persona"></div>
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label for="serie">Rubro</label>
								<?= form_dropdown('rubros_id', $rubros, $venta->rubros_id, array('class' => 'form-control')) ?>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="serie">Tipo Alumno</label>
								<?= form_dropdown('tipo_alumnos_id', $tipo_alumnos, $venta->tipo_alumnos_id, array('class' => 'form-control')) ?>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-4">
								<label for="serie">Condicion</label>
								<?= form_dropdown('condiciones_id', $condiciones, $venta->condiciones_id, array('class' => 'form-control', 'id' => 'condiciones_id')) ?>
							</div>
						</div>


					</div>

				</fieldset>

				<div id="apoderado">
					<fieldset>
						<legend>Datos del apoderado</legend>
					</fieldset>
					<div id="s2-apoderado"></div>
				</div>

				<fieldset>
					<legend>Datos del pago</legend>

					<div class="row">
						<div class="col-sm-3">
							<div class="form-group">
								<label for="esBecado">¿Aplicar beca?</label>
								<div class="checkbox">
									<label>
										<?= form_checkbox('esBecado', 1, $venta->alum_es_becado, ['id' => 'esBecado']); ?>
										Si
									</label>
								</div>
							</div>
						</div>
						<div id="caja-descuento" class="col-sm-9">
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label for="serie">Tipo Pago</label>
										<?= form_dropdown('tipo_pago_id', $tipo_pagos, $venta->tipo_pago_id, array('class' => 'form-control')) ?>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label for="tieneDescuento">¿Tiene descuento?</label>
										<div class="checkbox">
											<label>
												<?= form_checkbox('tieneDescuento', 1, $venta->tieneDescuento, ['id' => 'tieneDescuento']); ?>
												Si
											</label>
										</div>
									</div>
								</div>
								<div id="caja-input-descuento" class="col-sm-4 hidden">
									<div class="form-group">
										<label for="serie">Descuento</label>
										<?= form_input(['name' => 'descuento', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->alum_descuento]); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</fieldset>

				<fieldset>
					<legend>Datos complementarios</legend>
					<div class="row">
						<div class="col-sm-2">
							<div class="form-group">
								<label for="serie">Fecha de <b>venta</b> </label>
								<?= form_input(array('name' => 'alum_fecha', 'class' => 'form-control', 'type' => 'date', 'value' => $venta->alum_fecha)) ?>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label for="serie">Fecha inscripcion</label>
								<?= form_input(array('name' => 'fecha_inscripcion', 'class' => 'form-control', 'type' => 'date', 'value' => $venta->fecha_inscripcion)) ?>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">Estado</label>
								<?= form_dropdown('habilitado', $estado, $venta->habilitado, array('class' => 'form-control', 'id' => 'habilitado')); ?>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="serie">Observacion</label>
								<?= form_input(array('name' => 'observacion', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->observacion)) ?>
							</div>
						</div>
					</div>
				</fieldset>

				<div class="form-group">
					<button type="submit" value="Guardar" class="btn btn-success btn-block btn-sm">
						<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar
					</button>
				</div>
				<?= form_close() ?>

				<?php
				if ($show_completar) {
					$this->load->view('Ventas/completar');
				}
				?>
			</div>
		</div>
	</section>
</div>