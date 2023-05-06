<div class="content-wrapper">
    <section class="content-header">
        <h1>
        <i class="fa fa-chevron-circle-left" onclick="history.back()"></i>  Panel de Atencion al Cliente
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url()?>"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Tablero Atencion al Cliente</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>Ventas</h3>
                        <p>Editar Datos</p>
                        <p>Imprimir Ficha</p>

                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <a href="<?= base_url().'Ventas/lista'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>Asignar Grupos</h3>
                        <p>Registro de alumno</p>
                        <p>Editar registro</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="<?= base_url().'grupos/grupos_alumnos'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
           
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-rosado">
                    <div class="inner">
                        <h3>Pago de Alumnos</h3>
                        <p>Agregar Pago</p>
                        <p>Ver Asistencias, Notas</p>
                    </div>
                    <div class="icon">
                        <i class="fa   fa-sticky-note "></i>
                    </div>
                    <a href="<?= base_url().'AreaAcademica/alumnos'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3>Lista de Alumnos</h3>
                        <p>Ver Detalle</p>
                        <p>Editar, Eliminar</p>
                    </div>
                    <div class="icon">
                        <i class="fa   fa-sticky-note "></i>
                    </div>
                    <a href="<?= base_url().'AreaAcademica/listarAlumnos'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
    </section>

</div>