<div class="content-wrapper">
    <section class="content-header">
        <h1>
        <i class="fa fa-chevron-circle-left" onclick="history.back()"></i>  Panel de Ventas
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url()?>"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Tablero Ventas</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-sm-12 col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>Ventas</h3>
                        <p>Nueva Venta</p>
                        <p>Editar Venta</p>

                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <a href="<?= base_url().'Ventas/lista'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            <!-- ./col -->
            <div class="col-sm-12 col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>Llamadas</h3>
                        <p>Registro de llamada</p>
                        <p>Lista de llamada</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-phone"></i>
                    </div>
                    <a href="<?= base_url().'Llamadas/lista'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

        </div>
    </section>

</div>