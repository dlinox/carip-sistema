<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>
        <ol class="add-buttons">
            <li>
                <div class="dropdown">
                    <button class="btn btn-primary btn-flat dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="fa fa-plus"></span> Crear Certificadods
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="<?= base_url() ?>certificados/personal_crear">Personal</a></li>
                        <li><a href="<?= base_url() ?>certificados">Grupo</a></li>
                    </ul>
                </div>
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <form class="ocform form-inline">
                    <div class="form-group row" style="width: 100%;">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search[value]" id="filtro" placeholder="Buscar" value="">
                        </div>
                        <div class="col-md-4 col-md-offset-4 text-right">
                            <?= form_dropdown('tipo_certificado', ['TODO', 'GRUPO', 'PERSONAL'], 'TODO', ['class' => 'form-control', 'id' => 'tipo', 'data-placeholder' => '* TODO'])   ?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <?php echo genDataTable('mitabla', $columns, true, true);
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>