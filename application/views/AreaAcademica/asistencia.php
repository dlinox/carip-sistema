<div class="content-wrapper">
	<input type="hidden" id="nombre_curso" value="" />
	<section class="content-header">
		<h1>
			<i class="fa fa-chevron-circle-left" onclick="history.back()"></i> Panel de Asistencia
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> Inicio</a></li>
			<li class="active">Asistencia</li>
		</ol>
	</section>
	<section class="content">
		<div class="row mb15">
			<?php
			if ($this->session->userdata("usua_tipo")  == 2 || $this->session->userdata("usua_tipo")  == 7) : ?>
				<div class="col-sm-3">
					<div class="form-group">
						<select id="selectDocente" class="" data-placeholder="Seleccione un Docente"></select>
					</div>
				</div>

				<div class="col-sm-3">
					<div class="form-group">
						<select id="selectCurso" class="form-control">
							<option disabled selected> -- Curso -- </option>
						</select>

					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<select id="selectPeriodo" class="form-control">
							<option disabled selected> -- Periodo -- </option>
						</select>

					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<select id="selectGrupo" class="form-control">
							<option disabled selected> -- Grupo -- </option>
						</select>
					</div>
				</div>
			<?php else : ?>
				<div id="box-cursoPeriodo"></div>
			<?php endif; ?>

			<div class="col-sm-2">
				<button class="btn btn-block btn-success" type="button" id="guardar">Guardar</button>
			</div>
		</div>
		<form id="form">
			<input type="hidden" name="sesiones" value="" />
			<input type="hidden" name="grup_id" value="" />
			<div class="box-body table-responsive no-padding">

				<table id="tablaasistencia" class="table responsive table-striped table-bordered display" style="width:100%">
					<thead>
						<tr>
							<th rowspan="2">N°</th>
							<th rowspan="2">APELLIDOS</th>
							<th rowspan="2">NOMBRES</th>
							<th id="tabla-titulo">ASISTENCIAS</th>
						</tr>
						<tr id="second-row"></tr>
					</thead>
					<tbody id="table-body">

					</tbody>
				</table>
			</div>

			<div class="form-group">
				<button id="agregar-asistencia" class="btn btn-block btn-success" type="button">Agregar una asistencia</button>
			</div>
			<div class="form-group">
				<button id="eliminar-asistencia" class="btn btn-block btn-warning" type="button">Eliminar la última asistencia</button>
			</div>
		</form>
	</section>
</div>