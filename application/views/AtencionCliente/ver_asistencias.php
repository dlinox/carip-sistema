<?php
echo form_open(base_url() . 'AtencionCliente/guardarAsistencias/' . $alum_id . '/' . $grup_id);
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
            <?php
            if(isset($asistencias))
            {
                $asistencias = explode(',', $asistencias);
                $size = sizeof($asistencias);
            ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <?php
                            for($i = 1; $i <= $size; $i++)
                            {
                            ?>
                                <th>Asistencia <?=$i;?></th>
                            <?php
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            for($i = 0; $i < $size; $i++)
                            {
                            ?>
                                <td>
                                    <input type="checkbox" disabled <?=$asistencias[$i] == 1 ? 'checked' : '';?> />
                                    <?=form_dropdown('hubo_bienvenida['.$i.']', $opciones_bienvenida, (isset($llamadas[$i]) && $llamadas[$i] == '1') ? '1' : '0', array('class' => 'form-control')); ?>
                                </td>
                            <?php
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
            }
            else
            {
            ?>
                <div class="alert alert-warning" role="alert">El alumno no tiene registrado ninguna nota</div>
            <?php
            }
            
            ?>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
        </div>
    </div>
</div>
<?php
echo form_close();
?>