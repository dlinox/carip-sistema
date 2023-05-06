<?php if ($this->router->class != 'login') :  ?>
	<footer class="main-footer">
		<div class="pull-right hidden-xs">
			<b>Version</b> 1.1.0
		</div>
		<strong>Sistema Academico CARIP PERÚ</strong> <a target="_blank" href="#"></a></strong>
	</footer>
	</div>
<?php endif;  ?>
<input type="hidden" id="baseurl" value="<?php echo base_url() ?>">
<input type="hidden" id="nameurl" value="<?php echo current_url() ?>">
<!-- jQuery 3 -->
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/moment.min.js"></script>
<script src="<?= base_url() ?>assets/js/select2.min.js"></script>
<script src="<?= base_url() ?>assets/js/adminlte.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/js/dataTables.bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/demo.js"></script>
<script src="<?= base_url() ?>assets/js/jspdf.min.js"></script>
<script src="<?= base_url() ?>assets/sweetalert/sweetalert.min.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker.js"></script>
<script src="<?= base_url() ?>assets/js/comun.js"></script>
<script src="<?= base_url() ?>assets/js/general.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-colorpicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.tmpl.min.js"></script>
<script src="<?= base_url() ?>assets/js/chart.min.js"></script>
<script src="<?= base_url() ?>assets/js/myPlugins/s2alumno.plugin.js"></script>
<script src="<?= base_url() ?>assets/js/myPlugins/s2persona.plugin.js"></script>
<script src="<?= base_url() ?>assets/js/myPlugins/s2docente.plugin.js"></script>
<script src="<?= base_url() ?>assets/js/myPlugins/s2producto.plugin.js"></script>
<script src="<?= base_url() ?>assets/js/myPlugins/mySelect2.plugin.js"></script>
<script src="<?= base_url() ?>assets/js/myPlugins/cursoPeriodo.plugin.js"></script>
<script src="<?= base_url() ?>assets/js/myPlugins/myDateRangePicker.plugin.js"></script>

<script src="<?= base_url() ?>assets/js/myPlugins/pagos.plugin.js"></script>

<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.11.5/api/sum().js"></script>

<?php
$js = $this->cssjs->generate_js();
echo isset($js) ? $js : "";
?>
</body>

</html>