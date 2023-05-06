var url = "";
$(document).ready(function () {
    var url = nameurl + '?json=true';
    var $table;
    
    let select_categoria = $('#selectCategoria');
    select_categoria.mySelect2({
        url: baseurl + '/areaAcademica/s2GetCategorias',
        onSelect: () => { console.log('Filtrado por categoria'); },
        minimumInputLength: 0,
        placeholder: 'Seleccione una Categoria'
    });

    $('#lider').select2();
    $('#asesorado').select2();

    $('#lider').on('select2:select', function () {
        var lider_id = $('#lider').val(),
            asesorado_id = $('#asesorado').val();
        if (lider_id == asesorado_id) {
            $('#asesorado').val(null).trigger('change');
        }
    });

    $('#asesorado').on('select2:select', function () {
        var lider_id = $('#lider').val(),
            asesorado_id = $('#asesorado').val();
        if (lider_id == asesorado_id) {
            $('#lider').val(null).trigger('change');
        }
    });

    $('form[action="asignar_usuario"]').submit(function () {
        var $this = $(this);
        $this.formPost(true, {}, function (data) {
            if (data.exito) {
                swal("", data.mensaje, "success");
                // $('.select2-results__option[aria-selected=true]').css("border", "2px solid red");
                $('#asesorado').find('[value="' + $('#asesorado').val() + '"]').remove();
                //$('#asesorado').val(null).trigger('change');
                $('#asesorado').select2();
            } else {
                $this.find('.error-message-paquete').html(data.mensaje);
                $this.find('.error-message-paquete').removeClass('hidden');
            }
        });
        return false;
    });

    function botones(id, $ar) {
        html = `<a type="button" href='{baseurl}usuario/crear/{id}' title="Editar usuario" class="btn btn-primary btn-sm editar"><span class="glyphicon glyphicon-edit"></span></a>`;
        html += `<a type="button" href='{baseurl}usuario/eliminar/{id}' title="Eliminar usuario" class="btn btn-danger btn-sm eliminar"><span class="glyphicon glyphicon-trash"></span></a>`;
        // html +=`<a type="button" href='{baseurl}usuario/eliminar_asesor/{id}'title="remover asesor" class="btn btn-warning btn-sm eliminar"><span class="glyphicon glyphicon-trash"></span></a>`;
        html = replaceAll(html, "{baseurl}", baseurl);
        html = replaceAll(html, "{id}", id);
        $ar.append(html);

        $ar.find('.editar').click(function () {
            $(this).load_dialog({
                title: $(this).attr("title"),
                script: baseurl + "assets/js/usuario/formulario.js",
                loaded: function ($dlg) {
                    $dlg.find('form').submit(function () {
                        $(this).formPost(true, {}, function (data) {
                            if (data.exito) {
                                swal("", data.mensaje, "success");
                                $dlg.find('.close').click();
                                $table.draw('page');
                            } else {
                                $dlg.find('.error').html(data.mensaje);
                                $dlg.find('.error').show();
                            }
                        });
                        return false;
                    })
                }
            });
            return false;
        });
        $ar.find('.eliminar').click(function (e) {
            e.preventDefault();
            $this = $(this);
            swal({
                title: "",
                text: "¿Seguro que desea eliminar el usuario?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Eliminar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            }, function () {
                //swal("Deleted!", "Your imaginary file has been deleted.", "success");
                $.gs_loader.show();
                $.getJSON($this.attr('href'), function (data) {
                    $.gs_loader.hide();
                    if (data.exito) {
                        swal("", data.mensaje, "success");
                        $table.draw('page');
                    } else
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
            onrow: function (row, data) {
                botones(data.DT_RowId, $(row).find('td .opts'));
            }
        };

    var buton = "<div class='opts'></div>";
    $table = $dt.load_simpleTable(conf, true, buton);
    $('.ocform input').DTFilter($table);

    $('.ocform input,.ocform select').change(function () {

        $table.draw();
        return false;
    })

    $('.crear').on('click', function () {
        $(this).load_dialog({
            title: $(this).attr("title"),
            loaded: function ($dlg) {
                $dlg.find('form').submit(function () {
                    $(this).formPost(true, {}, function (data) {
                        if (data.exito) {
                            swal("", data.mensaje, "success");
                            $dlg.find('.close').click();
                            $table.draw('page');
                        } else {
                            $dlg.find('.error').html(data.mensaje);
                            $dlg.find('.error').show();
                        }
                    });
                    return false;
                })
            }
        });
        return false;
    })
    // $('#asesor').select2({
    //     placeholder: 'Asesores no asignados',
    //     delay: 777, 
    //     allowClear: true,
    //     width: '100%',
    //     language: "es",
    //     // dropdownParent: $(".modal-dialog"),
    //     minimumInputLength: Infinity,
    //     ajax: 
    //     {
    //         url: baseurl + "usuario/buscar_asesor",
    //         dataType: 'json',
    //         data: function (params) 
    //         {
    //             return {
    //                 term: params.term, 
    //                 lider: $('select[name="lider"]').val()
    //             };
    //         },
    //         processResults: function (data, params) 
    //         {
    //             params.page = params.page || 1;
    //             return {
    //                 results: data.items,
    //                 pagination: {
    //                     more: (params.page * 30) < data.total_count
    //                 }
    //             };
    //         },
    //         cache: true
    //     },
    //     escapeMarkup: function (markup) {
    //         return markup;
    //     },
    //     minimumInputLength: 0,
    // });

    // if ($('#s_id').val() != "") {
    //     $('#asesor').select2("trigger", "select", {
    //         data: { id: $('#s_id').val(), text: $('#s_name').val()}
    //     });
    // }
    // $('select[name="lider"]').change(function () {
    //     if ($(this).val() == "" || $(this).val() == 0) {
    //         $('#asesor').attr("disabled", "disabled");
    //     } else {
    //         $('#asesor').removeAttr("disabled");
    //     }
    //     $('#asesor').select2("trigger", "select", {
    //         data: { id: "", text: "" }
    //     });
    // })
});