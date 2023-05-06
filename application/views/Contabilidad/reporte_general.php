<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-chevron-circle-left" onclick="history.back()"></i> <?= $titulo ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <form class="ocform form-inline">
                    <div class="form-group">
                        <label for="rango">Desde / Hasta: </label>
                        <input class="form-control" type="text" id="rango" name="rango" />
                    </div>

                    <div class="form-group">
                        <?= form_dropdown('categoria', [], '', array('class' => 'form-control', 'id' => 'selectCategoria')); ?>
                    </div>

                </form>
            </div>
            <div class="box-body">
                <table id="table-simple" class="display table table-bordered" style="width:100%">

                    <thead>
                        <tr>
                            <th></th>
                            <th>Fuente</th>
                            <th>Descripción</th>
                            <th>Tipo</th>
                            <th>Importe</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                </table>

                    <div class="pull-right text-lg"> <b>TOTAL LIQUIDEZ: S/. <span id="cap-total"></b>  </span></div>
            </div>
        </div>

    </section>

</div>