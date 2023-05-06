<div class="content-wrapper">
	<section class="content">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> REGISTRO DE NUEVO GRUPO</h3>
				</div>
				<div class="box-body">
					<?= form_open(base_url() . 'grupos/guardar/' . $grupo->grup_id) ?>
					<input type="hidden" name="_prod_id" id="_prod_id" value="<?= $grupo->id; ?>" />
					<input type="hidden" name="_prod_id" id="_peri_id" value="<?= $grupo->peri_id; ?>" />
					<input type="hidden" name="_docente_id" id="_docente_id" value="<?= $grupo->grup_docente_id; ?>" />
					<div class="alert alert-danger error-message-paquete hidden" role="alert"></div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
								<label for="selectCategoria">Categoria</label>
									<?= form_dropdown('cate_id', [], '', array('class' => 'form-control', 'id' => 'selectCategoria')) ?>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Curso</label>
									<?= form_dropdown('curso', [], '', array('class' => 'form-control', 'id' => 'curso')); ?>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="form-group">
									<label class="control-label">Periodo</label>
									<?= form_dropdown('periodo', [], '', array('class' => 'form-control', 'id' => 'periodo', 'disabled' => 'disabled')); ?>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="form-group">
									<label for="serie">Nombre del grupo</label>
									<?= form_input(array('name' => 'nombre', 'class' => 'form-control', 'type' => 'text', 'value' => $grupo->grup_nombre)) ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Días</label>
									<div class="dropdown">
										<button class="btn btn-primary btn-block dropdown-toggle" type="button" data-toggle="dropdown">
											Seleccione los días
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu checkbox-menu allow-focus">
											<?php
											$dias_size = sizeof($dias);
											for ($i = 0; $i < $dias_size; $i++) {
											?>
												<li>
													<div class="checkbox">
														<label>
															<input name="dias[<?= $i; ?>]" type="checkbox" <?= ($grupo->grup_dias[$i] == 1) ? 'checked' : ''; ?>> <?= $dias[$i]; ?>
														</label>
													</div>
												</li>
											<?php
											}
											?>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="hora">Hora</label>
									<?= form_input(array('name' => 'hora', 'class' => 'form-control', 'type' => 'time', 'value' => $grupo->grup_hora)) ?>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Docente Asignado</label>
									<?= form_dropdown('docente_id', [], '', array('class' => 'form-control', 'id' => 'docente')); ?>
								</div>
							</div>


							<div class="col-sm-4">
								<div class="form-group">
									<label for="serie">Fecha de inicio</label>
									<?= form_input(array('name' => 'fecha_ini', 'class' => 'form-control', 'type' => 'date', 'value' => $grupo->grup_fechacrea)) ?>
								</div>
							</div>
						</div>
						<button type="submit" value="Guardar" class="btn btn-success">
							<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar
						</button>
					</div>
					<?= form_close() ?>
				</div>
			</div>
		</div>
	</section>
</div>