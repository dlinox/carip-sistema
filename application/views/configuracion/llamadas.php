<div class="content-wrapper">
	<section class="content-header">
		<h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>
	</section>
	<section class="content">
		<div class="box">
			<div class="box-header with-border">
				<form class="ocform form-inline">
					<input class="form-control" type="month" id="mes" name="mes" value="<?php echo date('Y-m'); ?>">
					<div class="form-group">
						<?= form_dropdown('usuario', $usuarios, '', array('class' => 'form-control select2', 'id' => 'lider', 'style' => "max-width: 400px")); ?>
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