<div class="content-wrapper">
	<section class="content-header">
		<h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>

		<ol class="add-buttons">
			<li>
				<a href="<?php echo base_url() ?>grupos/crear" id="realizar_venta" class="btn btn-success">
					<span class="fa fa-plus"></span> Registrar
				</a>
			</li>
		</ol>

	</section>
	<section class="content">
		<div class="box">
			<div class="box-header with-border">

				<form class="ocform ">
					<div class="row">
						<!--<div class="col-sm-12 col-md-6 col-lg-4">
							<?= form_dropdown('curso', [], '', array('class' => 'form-control', 'id' => 'curso')); ?>
						</div> -->

						<div id="box-curso-periodo">
							<div class="col-sm-3">
								<div class="form-group">
									<?= form_dropdown('categoria', [], '', array('class' => 'form-control', 'id' => 'selectCategoria', 'data-placeholder' => 'Categoria')); ?>
								</div>
							</div>
						</div>
					</div>
					<!-- <input class="form-control" type="month" id="mes" name="mes" value="<?php echo date('Y-m'); ?>" > -->

				</form>
			</div>


			<!-- Modal VER DETALLES DE GRUPO -->
			<div class="modal fade" id="modal-ver-detalles" tabindex="-1" aria-labelledby="ver_detalles" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="ver_detalles">Detalles del grupo</h5>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-5 text-right"><b>Nombre del Curso: </b></div>
								<div class="col-xs-7">
									<p id="modal-nombre-curso"></p>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-5 text-right"><b>Periodo: </b></div>
								<div class="col-xs-7">
									<p id="modal-periodo"></p>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-5 text-right"><b>Grupo: </b></div>
								<div class="col-xs-7">
									<p id="modal-grupo"></p>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-5 text-right"><b>Dias: </b></div>
								<div class="col-xs-7">
									<p id="modal-dias"></p>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-5 text-right"><b>Hora: </b></div>
								<div class="col-xs-7">
									<p id="modal-hora"></p>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-5 text-right"><b>Docente Asignado: </b></div>
								<div class="col-xs-7">
									<p id="modal-docente-asignado"></p>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							<button type="button" id="btn-imprimir-detalle" data-id class="btn btn-primary">Imprimir</button>
						</div>
					</div>
				</div>
			</div>

			<div class="box-body">
				<div class="table-responsive">
					<?php echo genDataTable('mitabla', $columns, true, true); ?>
				</div>
			</div>
		</div>
	</section>
</div>