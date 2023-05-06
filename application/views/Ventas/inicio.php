<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-chevron-circle-left" onclick="history.back()"></i> Panel de Control
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Tablero Principal</li>
        </ol>
    </section>

    <section class="content">

        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua">
                        <i class="fas fa-user-graduate"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">ALUMNOS</span>

                        <?php foreach ($total_alumnos as $item) : ?>
                            <span class="info-box-number"><?= $item->cantidad ?> <small> - <?= $item->cate_nombre ?> </small></span>
                        <?php endforeach; ?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fas fa-user-shield"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">USUARIOS</span>
                        <?php foreach ($total_usuarios as $item) : ?>
                            <span class="info-box-number"><?= $item->cantidad ?> <small> - <?= $item->cate_nombre ?> </small></span>
                        <?php endforeach; ?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fas fa-laptop-code"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">CURSOS</span>
                        <?php foreach ($total_cursos as $item) : ?>
                            <span class="info-box-number"><?= $item->cantidad ?> <small> - <?= $item->cate_nombre ?> </small></span>
                        <?php endforeach; ?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fas fa-building"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">categorias</span>
                        <?php foreach ($categorias as $item) : ?>
                            <span class="info-box-number"> <small> <?= $item->cate_nombre ?> </small></span>
                        <?php endforeach; ?>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- =========================================================== -->

        <div class="row">


            <div class="col-md-12">
                <div class="box box-aqua">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ventas del año por categoria</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <canvas id="salesChart" style="max-height: 300px;"></canvas>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cursos más vendidos</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <?php foreach ($cursos_populares as $item) : ?>
                        <div class="info-box box-info mb-0">
                            <span class="info-box-icon bg-yellow">
                                <?= $item->cantidad ?>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text"><?= $item->cate_nombre ?></span>
                                <span class="info-box-number"><?= $item->nombre ?></span>

                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="progress-description">
                                    <?= $item->cantidad ?> ventas
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    <?php endforeach; ?>

                    <!-- /.box-body -->
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua">
                    <i class="fas fa-hand-holding-usd"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">INGRESOS POR DESCUENTOS</span>
                             <h3> S/.
                             <?= $descuentos->total ?> <small>  / desde siempre </small>
                             </h3>
                    </div>
                    <!-- /.info-box-content -->
                </div>

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Docentes con mas grupo</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <?php foreach ($docentes as $item) : ?>
                        <div class="info-box box-info mb-0">
                            <span class="info-box-icon bg-green">
                                <?= $item->cantidad ?>
                            </span>
                            <div class="info-box-content ">
                                <span class="info-box-text">DOCENTE</span>
                                <span class="info-box-number"><?= $item->nombres ?></span>

                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                                <span class="progress-description">
                                    <?= $item->cantidad ?> grupos
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    <?php endforeach; ?>

                    <!-- /.box-body -->
                </div>
            </div>

        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>Ventas</h3>
                        <p>Llamadas</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <a href="<?= base_url() . 'Ventas/lista' ?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>Atención al Cliente</h3>
                        <p>Ventas, Asignar grupos, Pagos Alumnos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cash-register"></i>

                    </div>
                    <a href="<?= base_url() . 'AreaAcademica/alumnos' ?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->


            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3>Cobranza</h3>
                        <p>Pagos Alumnos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cash-register"></i>

                    </div>
                    <a href="<?= base_url() . 'AreaAcademica/alumnos' ?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->


            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>Grupos</h3>
                        <p>Asignar Grupos</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-institution"></i>
                    </div>
                    <a href="<?= base_url() . 'grupos/lista' ?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>Area Academica</h3>
                        <p>Asistencia, Notas</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-folder"></i>
                    </div>
                    <a href="<?= base_url() . 'AreaAcademica/alumnos' ?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-celeste">
                    <div class="inner">
                        <h3>Contabilidad</h3>
                        <p>Pagos, Reportes</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>
                    <a href="<?= base_url() . 'Contabilidad/resumenmensual' ?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-rosado">
                    <div class="inner">
                        <h3>Usuarios</h3>
                        <p>Agregar Personal</p>
                    </div>
                    <div class="icon">
                        <i class="fa  fa-users "></i>
                    </div>
                    <a href="<?= base_url() . 'usuario/listado' ?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>Configuracion</h3>
                        <p>Cursos</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-cog"></i>
                    </div>
                    <a href="<?= base_url() . 'configuracion/producto' ?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->



        </div>
    </section>

</div>