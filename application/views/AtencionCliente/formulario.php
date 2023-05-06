<div class="content-wrapper">
	<section class="content">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"> <i class="fa fa-chevron-circle-left" onclick="history.back()"></i> REGISTRO DE NUEVO ALUMNO </h3>
				</div>
				<div class="box-body">
					<?= form_open(base_url() . 'AtencionCliente/guardar/' . $venta->id_alumno) ?>
					<div class="alert alert-danger error-message-paquete hidden" role="alert"></div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-3">
								<label for="serie">Rubro</label>
								<?= form_dropdown('rubros_id', $rubros, $venta->rubros_id, array('class' => 'form-control')) ?>
							</div>

							<div class="col-sm-3">
								<label for="serie">Producto</label>
								<?= form_dropdown('productos_id', $productos, $venta->productos_id, array('class' => 'form-control')) ?>
							</div>
							<div class="col-sm-6">
								<label class="control-label">Estado</label>
								<?= form_dropdown('habilitado', $estado, $venta->habilitado, array('class' => 'form-control', 'id' => 'habilitado')); ?>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div class="col-sm-2">
									<label for="serie">DNI</label>
									<?= form_input(array('name' => 'dni', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->dni)) ?>
								</div>
								<div class="col-sm-4">
									<label for="serie">Nombres</label>
									<?= form_input(array('name' => 'nombre', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->nombre)) ?>
								</div>
								<div class="col-sm-6">
									<label for="serie">Apellidos</label>
									<?= form_input(array('name' => 'apellidos', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->apellidos)) ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label for="serie">Celular</label>
									<?= form_input(array('name' => 'celular', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->celular)) ?>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="serie">Fecha inscripcion</label>
									<?= form_input(array('name' => 'fecha_inscripcion', 'class' => 'form-control', 'type' => 'date', 'value' => $venta->fecha_inscripcion)) ?>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="serie">Cantidad de cuotas</label>
									<?= form_input(array('name' => 'cuotas', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->cuotas)) ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label for="serie">Tipo Alumno</label>
									<?= form_dropdown('tipo_alumnos_id', $tipo_alumnos, $venta->tipo_alumnos_id, array('class' => 'form-control')) ?>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="serie">Tipo Pago</label>
									<?= form_dropdown('tipo_pago_id', $tipo_pagos, $venta->tipo_pago_id, array('class' => 'form-control')) ?>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="serie">Condicion</label>
									<?= form_dropdown('condiciones_id', $condiciones, $venta->condiciones_id, array('class' => 'form-control')) ?>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label for="serie">Correo Electronico</label>
									<?= form_input(array('name' => 'correo_electronico', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->correo_electronico)) ?>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="serie">Pais</label>
									<?= form_dropdown('paises_id', $paises, $venta->paises_id, array('class' => 'form-control')) ?>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="serie">Departamento</label>
									<?= form_dropdown('departamentos_id', $departamentos, $venta->departamentos_id, array('class' => 'form-control')) ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label for="serie">Direccion</label>
									<?= form_input(array('name' => 'direccion', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->direccion)) ?>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="serie">C. Laboral</label>
									<?= form_input(array('name' => 'centro_laboral', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->centro_laboral)) ?>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="serie">Fecha de nacmiento</label>
									<?= form_input(array('name' => 'fecha_nacimiento', 'class' => 'form-control', 'type' => 'date', 'value' => $venta->fecha_nacimiento)) ?>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group">
								<div class="col-sm-2">
									<button type="submit" value="Guardar" class="btn btn-success btn-sm">
										<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar
									</button>
								</div>
							</div>
						</div>


						<div id="apoderado">
							<div class="box-header with-border">
								<h3 class="box-title">REGISTRO DEL APODERADO </h3>
							</div>
							<div class="row">
								<div class="form-group">
									<div class="col-sm-2">
										<label for="serie">DNI</label>
										<?= form_input(array('name' => 'titular_dni', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->titular_dni)) ?>
									</div>
									<div class="col-sm-4">
										<label for="serie">Nombres</label>
										<?= form_input(array('name' => 'titular_nombre', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->titular_nombre)) ?>
									</div>
									<div class="col-sm-6">
										<label for="serie">Apellidos</label>
										<?= form_input(array('name' => 'titular_apellidos', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->titular_apellidos)) ?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-3">
									<div class="form-group">
										<label for="serie">Celular</label>
										<?= form_input(array('name' => 'titular_celular', 'class' => 'form-control', 'type' => 'text', 'value' => $venta->titular_celular)) ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?= form_close() ?>
				</div>
			</div>
		</div>
	</section>
</div>