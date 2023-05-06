var url = "";
$(document).ready(function(){

    let select_categoria = $('#selectCategoria');
    select_categoria.mySelect2({
        url: baseurl + '/areaAcademica/s2GetCategorias',
        onSelect: () => { console.log(''); },
        minimumInputLength: 0
    });

    var url = nameurl + '?json=true';
    var $table;
    function botones(id, $ar) {
        html = `
        <a href='{baseurl}configuracion/producto_crear/{id}' title="Editar producto" class="btn btn-primary btn-sm editar"><i class="fa fa-edit"></i></a>
        <a href='{baseurl}configuracion/producto_agregar_periodo/{id}' title="Agregar periodo" class="btn btn-success btn-sm agregar-periodo"><i class="fa fa-plus"></i></a>
        <a href='{baseurl}configuracion/producto_eliminar_periodo/{id}'title="Eliminar periodo" class="btn btn-warning btn-sm eliminarperiodo"><i class="fa fa-minus"></i></a>
        <a href='{baseurl}configuracion/producto_eliminar/{id}'title="Eliminar producto" class="btn btn-danger btn-sm eliminar"><i class="fa fa-trash"></i></a>`;
        
        html = replaceAll(html, "{baseurl}", baseurl);
        html = replaceAll(html, "{id}", id);
        $ar.append(html);
        
        $ar.find('.editar').click(function() {
            $(this).load_dialog({
                title : $(this).attr("title"),
                loaded: function($dlg) {
                    $dlg.find('form').submit(function() {
                        $(this).formPost(true, {}, function(data) {
                            if(data.exito){
                                swal("", data.mensaje, "success");
                                $dlg.find('.close').click();
                                $table.draw('page');
                            }else{
                                $dlg.find('.error-message').html(data.mensaje);
                                $dlg.find('.error-message').show();
                            }
                        });
                        return false;
                    })
                }
            });
            return false;
        });

        $ar.find('.agregar-periodo').click(function(e) {
            e.preventDefault();
            $this = $(this);
            swal({
                title: "",
                text: "¿Desea agregar otro periodo?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                confirmButtonText: "Agregar",
                cancelButtonText: "Cancelar",
                cancelButtonClass: "btn-secondary",
                closeOnConfirm: false
            },function(){
                $.gs_loader.show();
                $.getJSON($this.attr('href'), function (data) {
                    $.gs_loader.hide();
                    if(data.exito){
                        swal("", data.mensaje, "success");
                        $table.draw('page');    
                    }else
                        swal("", data.mensaje, "error");
                });
            });
            return false;
        });

        $ar.find('.eliminar').click(function(e) {
            e.preventDefault();
            $this = $(this);
            swal({
                title: "",
                text: "¿Seguro que desea eliminar el cliente?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Eliminar",
                cancelButtonText: "Cancelar",
                cancelButtonClass: "btn-secondary",
                closeOnConfirm: false
            },function(){
                $.gs_loader.show();
                $.getJSON($this.attr('href'), function (data) {
                    $.gs_loader.hide();
                    if(data.exito){
                        swal("", data.mensaje, "success");
                        $table.draw('page');    
                    }else
                        swal("", data.mensaje, "error");
                });
            });
            return false;
        });

        $ar.find('.eliminarperiodo').click(function(e) {
            e.preventDefault();
            $this = $(this);
            swal({
                title: "",
                text: "¿Seguro que desea eliminar el periodo?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Eliminar",
                cancelButtonText: "Cancelar",
                cancelButtonClass: "btn-secondary",
                closeOnConfirm: false
            },function(){
                $.gs_loader.show();
                $.getJSON($this.attr('href'), function (data) {
                    $.gs_loader.hide();
                    if(data.exito){
                        swal("", data.mensaje, "success");
                        $table.draw('page');    
                    }else
                        swal("", data.mensaje, "error");
                });
            });
            return false;
        });

    }
    
    var $dt = $('#mitabla'),
        conf = {
            data_source: url,
            cactions: ".ocform",
            order: [
                [1, "desc"]
            ],
            onrow: function(row, data) {
                botones(data.DT_RowId,$(row).find('td .opts'));
            }
        };

    var buton = "<div class='opts'></div>";
    // $table = $dt.load_simpleTable(conf, true, buton);
    $table = $dt.load_simpleTable(conf, true, buton);
    $('.ocform input').DTFilter($table);

    $('.ocform input,.ocform select').change(function() {
        
        $table.draw();
        return false;
    })
    
    $('.crear').on('click', function(){
        $(this).load_dialog({
            title : $(this).attr("title"),
            loaded: function($dlg) {
                $dlg.find('form').submit(function() {
                    $(this).formPost(true, {}, function(data) {
                        if(data.exito){
                            swal("", data.mensaje, "success");
                            $dlg.find('.close').click();
                            $table.draw('page');
                        }else{
                            $dlg.find('.error-message').html(data.mensaje);
                            $dlg.find('.error-message').show();
                        }
                    });
                    return false;
                })
            }
        });
        return false;
    })
});