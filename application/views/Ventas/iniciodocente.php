<div class="content-wrapper">
    <section class="content-header">
        <h1>
        <i class="fa fa-chevron-circle-left" onclick="history.back()"></i>  Panel de Docente
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url()?>"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Tablero Docente</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>Asistencia</h3>
                        <p>Agregar Asistencia</p>
                        <p>Editar Asistencia</p>

                    </div>
                    <div class="icon">
                        <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <a href="<?= base_url().'AreaAcademica/asistencia'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            
           
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-rosado">
                    <div class="inner">
                        <h3>Notas</h3>
                        <p>Agregar Notas</p>
                        <p>EditarNotas</p>
                    </div>
                    <div class="icon">
                        <i class="fa   fa-sticky-note "></i>
                    </div>
                    <a href="<?= base_url().'AreaAcademica/asistencia'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>Pago de Alumnos</h3>
                        <p>Lista de cursos</p>
                        <p>Lista de Alumnos</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="<?= base_url().'AreaAcademica/alumnos'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
    </section>

</div>