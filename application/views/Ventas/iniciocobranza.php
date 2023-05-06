<div class="content-wrapper">
    <section class="content-header">
        <h1>
        <i class="fa fa-chevron-circle-left" onclick="history.back()"></i>  Panel de Cobranza
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url()?>"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Tablero Cobranza</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>Pagos de Alumnos</h3>
                        <p>Agregar Pago</p>
                        <p>Reporte de Pago</p>

                    </div>
                    <div class="icon">
                        <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <a href="<?= base_url().'AreaAcademica/asistencia'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>Editar Pago</h3>
                        <p>Reporte de Ingresos</p>
                        <p>Eliminar Pago<p/>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="<?= base_url().'AreaAcademica/alumnos'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
           
        </div>
    </section>

</div>