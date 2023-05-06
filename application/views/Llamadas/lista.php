<div class="content-wrapper">
	<section class="content-header">
		<h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>
		<ol class="add-buttons">
			<li>
				<a href="<?php echo base_url() ?>Llamadas/crear" id="realizar_venta" type="button" class="btn btn-success pull-right">
					Registrar
				</a>
			</li>
		</ol>

	</section>
	<section class="content">
		<div class="box">
			<div class="box-header with-border">
				<form class="ocform">
					<div class="row">
						<div class="col-6 col-sm-3">
							<div class="form-group">
								<label for="rango">Desde / Hasta: </label>
								<input class="form-control" type="text" id="rango" name="rango" />
							</div>
						</div>
						<div class="col-6 col-sm-3">
							<div class="form-group">
								<label for="selectCategoria">Categoria: </label>
								<?= form_dropdown('cate_id', [], '', array('class' => 'form-control', 'id' => 'selectCategoria')) ?>
							</div>
						</div>
						<div class="col-6 col-sm-3">
							<div class="form-group">
								<label for="respuestas_id">Respuesta: </label>
								<?= form_dropdown('respuestas_id', $estado, $llamada->respuestas_id, array('class' => 'form-control', 'id' => 'respuestas_id')); ?>
							</div>
						</div>
						
						<div class="col-6 col-sm-2">
							<div class="form-group">
								<label for="concretado"> Concretado: </label>
								<select class="form-control" name="concretado">
									<option value="0">No</option>
									<option value="1">SI</option>
								</select>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<?php echo genDataTable('mitabla', $columns, true, true); ?>
				</div>
			</div>
		</div>
	</section>
</div>