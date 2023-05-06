<div class="content-wrapper">
    <section class="content-header">
        <h1>
        <i class="fa fa-chevron-circle-left" onclick="history.back()"></i>  Panel de Contabilidad
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url()?>"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Tablero Contabilidad</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>Flujo de Caja</h3>
                        <p>Registro de gastos</p>
                        <p>Reporte de gastos</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <a href="<?= base_url().'Contabilidad/flujo_caja'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>Pago al Personal</h3>
                        <p>Pagar</p>
                        <p>Lista</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-institution"></i>
                    </div>
                    <a href="<?= base_url().'Contabilidad/pagopersonal'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>Pagos de Alumnos</h3>
                        <p>Lista de Pagos, Reporte</p>
                        <p>Editar Pagos</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-folder"></i>
                    </div>
                    <a href="<?= base_url().'Contabilidad/ingresos'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-celeste">
                    <div class="inner">
                        <h3>Reporte Pagos</h3>
                        <p>Editar Pago de Personal</p>
                        <p>Eliminar Pago de Personal</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>
                    <a href="<?= base_url().'Contabilidad/reportepagos'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>Resumen Mensual</h3>
                        <p>Flujo de Ingresos y Egresos</p>
                        <p>Grafico de Ingresos/Egresos y Ventas</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-cog"></i>
                    </div>
                    <a href="<?= base_url().'Contabilidad/resumenmensual'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-rosado">
                    <div class="inner">
                        <h3>Resumen Anual</h3>
                        <p>Flujo de Ingreso y Egreso Anual</p>
                        <p>Grafico de Ingresos/Egresos y Ventas</p>
                    </div>
                    <div class="icon">
                        <i class="fa  fa-users "></i>
                    </div>
                    <a href="<?= base_url().'Contabilidad/resumenanual'?>" class="small-box-footer">Más Información <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
    </section>

</div>