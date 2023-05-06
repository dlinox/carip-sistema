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
            if (isset($notas)) {
                $_notas  = explode(',', $notas->notas);
                $_niveles  = explode(',', $notas->niveles);

                $size = sizeof($_niveles);
            ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <?php
                                foreach ($_niveles as $nivel) : ?>
                                    <th><?= $nivel ?></th>
                                <?php endforeach; ?>
                                <th> Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                foreach ($_notas as $nota) : ?>
                                    <td><?= $nota ?></td>
                                <?php endforeach; ?>
                                <td> <?= array_sum($_notas) / $size ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            <?php
            } else {
            ?>
                <div class="alert alert-warning" role="alert">El alumno no tiene registrado ninguna nota</div>
            <?php
            }
            ?>
        </div>
        <div class="modal-footer">
            <!-- <button type="submit" class="btn btn-default">Cerrar</button> -->
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>