<div class="content-wrapper">
    <input type="hidden" id="nombre_curso" value="" />
    <section class="content-header">
        <h1>
            <i class="fa fa-chevron-circle-left" onclick="history.back()"></i> Panel de certificados
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Certificados</li>
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

            <div class="box-body">
                <div class="row pb-3">
                    <div class="col-12 col-md-3">
                        <label>Categoria:</label>
                        <?= form_dropdown('cate_id', $categorias, null, array('class' => 'form-control', 'id' => 'selectCategoria')) ?>
                    </div>
                    <div class="col-12 col-md-3">
                        <label>Mención</label>
                        <?= form_dropdown('menc_id', $menciones, null, array('class' => 'form-control', 'id' => 'selectMencion')) ?>
                    </div>

                    <div class="col-12 col-md-6">
                        <label>Emicion</label>
                        <?= form_input(array('name' => 'fecha_emision', 'placeholder' => 'Fecha', 'class' => 'form-control', 'placeholder' => 'Arequipa, Enero de 2023')) ?>
                    </div>
                </div>
            </div>


            <input type="hidden" name="alumnos_ids" value="" />
            <input type="hidden" name="grup_id" value="" />
            <input id="input_nombre_curso" type="hidden" name="nombre_curso" value="" />
            <div class="box-body table-responsive no-padding">
                <table id="tablacertificados" class="table responsive table-striped table-bordered display" style="width:100%">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>APELLIDOS</th>
                            <th>NOMBRES</th>
                            <th>PREFIJO</th>
                            <th>CODIGO</th>
                            <th>CERTIFICADO</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">

                    </tbody>
                </table>
            </div>
        </form>
    </section>
</div>