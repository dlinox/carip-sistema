<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-chevron-circle-left" onclick="history.back()"></i> Panel de Atencion al Cliente
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Tablero Atencion al Cliente 2</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>Ventas</h3>

                        <p>Editar Datos, Imprimir Ficha</p>
                        

                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <a href="<?= base_url() . 'Ventas/lista' ?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

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

        </div>
    </section>

</div>