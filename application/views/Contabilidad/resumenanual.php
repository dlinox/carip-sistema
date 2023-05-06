<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>

    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">

            <div class="box-header">
                <form class="ocform form-inline">
                    <div class="form-group">
                        <label for="rango">Desde / Hasta: </label>
                        <input class="form-control" type="text" id="rango" name="rango" />
                    </div>

                    <div class="form-group">
                        <?= form_dropdown('categoria', [], '', array('class' => 'form-control', 'id' => 'selectCategoria')) ?>
                    </div>
                </form>
            </div>
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">FLUJO DE INGRESOS Y EGRESOS</h3>
                    
                </div>
                <div class="row container">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="fa  fa-arrow-circle-up"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">INGRESO TOTAL</span>
                                <span class="info-box-number ingresototal"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="fa  fa-arrow-circle-down"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">EGRESO TOTAL</span>
                                <span class="info-box-number egresototal"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">LIQUIDEZ</span>
                                <span class="info-box-number liquidez"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php ##echo genDataTable('mitabla', $columns, true, true); 
            ?>



        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">INGRESOS Y EGRESOS DEL AÑO</h3>


                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="myChart" width="100%" height="100%"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">VENTAS</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="myChart1" width="100%" height="100%"></canvas>
                    </div>

                </div>
            </div>

        </div>

    </section>
</div>