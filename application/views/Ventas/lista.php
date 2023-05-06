<input type="hidden" id="_tipo-usuario" value="<?= $tipo_usuario; ?>" />
<input type="hidden" id="_tiene-asesorados" value="<?= ($tieneAsesorados == true ? 1 : 0); ?>" />

<div class="content-wrapper">
	<section class="content-header">
		<h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>

		<ol class="add-buttons">
			<li>
				<?php if ($tipo_usuario == 1 || $tipo_usuario == 7) : ?>
					<a href="<?php echo base_url() ?>Ventas/crear" id="realizar_venta" type="button" class="btn btn-success pull-right">Registrar</a>
				<?php endif; ?>
			</li>
		</ol>
	</section>
	<section class="content">
		<div class="box">
			<div class="box-header with-border">
				<form class="ocform ">
					<div class="row">
						<div class="col-6 col-sm-3">
							<div class="form-group">
								<label for="selectCategoria">Categoria:</label>
								<?= form_dropdown('cate_id', [], '', array('class' => 'form-control', 'id' => 'selectCategoria')) ?>
							</div>
						</div>
						<div class="col-6 col-sm-4">
							<div class="form-group">
								<label for="search"> Buscar: </label>

								<input type="text" class="form-control" name="search[value]" id="filtro" placeholder="Buscar" value="">
							</div>
						</div>
						<div class="col-6 col-sm-3">

							<div class="form-group">
								<label for="rango">Desde / Hasta: </label>
								<input class="form-control" type="text" id="rango" name="rango" />
							</div>

						</div>
						<div class="col-6 col-sm-2">
							<?php if ($tipo_usuario == 2 || $tipo_usuario == 3 || $tipo_usuario == 7) : ?>
								<div class="form-group">
									<label for="completado">Completado: </label>
									<br>
									<select name="completado" id="completado" class="form-control">
										<option value="0"> No </option>
										<option value="1"> Si </option>
										<option value="2"> Ambos </option>
									</select>
								</div>
							<?php endif; ?>
						</div>

						<div class="col-6 col-sm-3">
							<?php if ($tipo_usuario == 2 || $tipo_usuario == 3) : ?>
								<div class="form-group">
									<label for="completado">Vendedor: </label>
									<?= form_dropdown('usua_id_vendor', [], '', array('class' => 'form-control', 'id' => 'selectVendedor')) ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</form>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<?php echo genDataTable('mitabla', $columns, true, true); ?>
				</div>

				<?php
				if ($tieneAsesorados) {
				?>
					<h3>Ventas de asesorados</h3>
					<div id="asesorados">
						<div class="table-responsive">
							<?php echo genDataTable('asesorados_tabla', $columns_asesorado, false, true); ?>
						</div>
					</div>
				<?php
				}
				?>

				<?php
				if ($tipo_usuario == 1) // Si es un usuario de ventas
				{
				?>
					<div class="row" style="margin-top:10px">
						<div class="col-sm-6">
						</div>
						<div class="col-sm-6">
							<div class="row">
								<div class="col-sm-10">
									<p class="text-right"><strong>COMISION DIRECTA: <span class="mone-simb"></span></strong></p>
								</div>
								<div class="col-sm-2">
									<p class="text-right"><strong>S/. <span class="comision_directa"></span></strong></p>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-10">
									<p class="text-right"><strong>COMISION ASESOR: <span class="mone-simb"></span></strong></p>
								</div>
								<div class="col-sm-2">
									<p class="text-right"><strong>S/. <span class="comision_asesor"></span></strong></p>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-10">
									<p class="text-right"><strong>TOTAL: <span class="mone-simb"></span></strong></p>
								</div>
								<div class="col-sm-2">
									<p class="text-right"><strong>S/. <span class="comision_total"></span></strong></p>
								</div>
							</div>
						</div>
					</div>
				<?php
				}
				?>
			</div>
		</div>
	</section>
</div>