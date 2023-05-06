<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
        <div class="box-header with-border">
            <form class="ocform form-inline" action="asignar_usuario" method="POST">
                <div class="alert alert-danger error-message-paquete hidden" role="alert"></div>
                <div class="form-group">
                    <input type="text" class="form-control" name="search[value]" id="filtro" placeholder="Buscar" value="">
                </div>
                <div class="form-group">
                    <!-- <?= form_dropdown('lider', [], '', array('class' => 'form-control', 'id' => 'lider', 'style' => "max-width: 400px")); ?> -->
                    <select name="lider" id="lider">
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group">
                    <?= form_dropdown('asesorado', [], '', array('class' => 'form-control', 'id' => 'asesorado', 'style' => "max-width: 400px")); ?>
                </div>
                <div class="form-group">
                    <input class="btn btn-success btn-flat" type="submit" name="asignar_usuario" value="Asignar Usuario">
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