var url = "";
var $table;
var baseurl;
$(document).ready(function () {

    baseurl = $("#baseurl").val();

    $('#curso').mySelect2({
        url: baseurl + 'producto/s2',
        onSelect: selectCurso,
        placeholder: 'Ingrese el termino de busqueda', 
    });

    $('#box-curso-periodo').cursoPeriodo();

    function selectCurso() {}

    var url = nameurl + '?json=true';

    function botones(id, $ar, data) {
        //

        let datos = data;
        let finalizar = data["ESTADO CURSO"] != "Finalizado" ? `<li><a href='{baseurl}Grupos/finalizar/{id}' title="Finalizar Grupo" class="finalizar"><i class="fa fa-check"></i>Finalizar</a></li>` : '';
        html = `
        <div class="btn-group">
            <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
            <li><a href='{baseurl}Grupos/detalle/{id}' title="Ver Grupo" class=""><i class="fa fa-eye" ></i>Ver Detalle</a></li>
            <li><a href='{baseurl}Grupos/crear/{id}' title="Editar Grupo" class="editar"><i class="fa fa-edit"></i>Editar</a></li>
            ${finalizar} 
            <li><a href='{baseurl}Grupos/eliminar/{id}' title="Eliminar Grupo" class="eliminar"><i class="fa fa-trash"></i>Eliminar</a></li>
            </ul>
        </div>`;
        html = replaceAll(html, "{baseurl}", baseurl);
        html = replaceAll(html, "{id}", id);
        $ar.append(html);

        $ar.find('.finalizar').click(function (e) {
            e.preventDefault();
            $this = $(this);
            swal({
                title: "",
                text: "¿Seguro que desea finalizar el grupo?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Finalizar",
                cancelButtonText: "Cancelar",
                cancelButtonClass: "btn-secondary",
                closeOnConfirm: false
            }, function () {
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

        $ar.find('.ver-detalles').click(function (e) {
            e.preventDefault();
            let dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo']
            $.ajax({
                url: baseurl + 'Grupos/getDetalleGrupo/' + datos['DT_RowId'],
                dataType: 'json',
            }).done((data) => {
                console.log(data);
                let _dias = "";
                let val_dias = data.grup_dias.split(',');
                val_dias.forEach((element, i) => {
                    element == 1 ? _dias += dias[i] + ", " : ''
                });
                _dias = _dias.substring(0, _dias.length - 2);
                $('#modal-nombre-curso').text(datos['CURSO'])
                $('#modal-periodo').text(datos['PERIODO'])
                $('#modal-grupo').text(datos['NOMBRE'] + " - " + datos['GRUPO'])
                $('#modal-dias').text(_dias)
                $('#modal-hora').text(data.grup_hora)
                $('#modal-docente-asignado').text(datos['DOCENTE'])
                $("#modal-ver-detalles").modal('show');

                $('#btn-imprimir-detalle').attr('data-id', datos['DT_RowId'])
            }).fail((error) => {
                console.log(error);
            })
        });

        $ar.find('.eliminar').click(function (e) {
            e.preventDefault();
            $this = $(this);
            swal({
                title: "",
                text: "¿Seguro que desea eliminar el grupo?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Eliminar",
                cancelButtonText: "Cancelar",
                cancelButtonClass: "btn-secondary",
                closeOnConfirm: false
            }, function () {
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

    $(document).on('click', '#btn-imprimir-detalle', function () {

        let id = $(this).attr('data-id');

        window.open(baseurl + 'Grupos/imprimirDetalleGrupo/' + id, '_blank');
   
    })

    var buton = "<div class='opts'></div>";
    var selected = [];
    var cols = Array();
    cols.push({
        "data": null,
        "orderable": false,
        "width": "30",
        'render': function (data, type, full, meta) {
            if (typeof (buton) === "undefined") {
                return '<input type="checkbox">';
            } else {
                return buton;
            }
        }
    })
    $('#mitabla').find('tr .ths').each(function (i, item) {
        cols.push({
            "data": $(item).text(),
            className: "edit"
        });
    })
    $table = $('#mitabla').DataTable({
        dom: "<'row'<'col-sm-6 col-lg-8'B><'col-sm-6col-lg-4 text-right'lf>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [{
                extend: 'excel',
                text: '<span><i class="fa fa-file-excel-o"></i> EXCEL</span>'
            },
            {
                extend: 'pdf',
                text: '<span><i class="fa fa-file-pdf-o"></i> PDF</span>',
                orientation: 'landscape',
            },
            {
                extend: 'print',
                text: '<span><i class="fa fa-print"></i> IMPRIMIR</span>'
            },
        ],
        "processing": true,
        "serverSide": true,
        "bResetDisplay": true,
        "order": [
            [3, "desc"]
        ],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "Todo"]
        ],
        "ajax": {
            "url": url,
            "type": "POST",
            "data": function (data) {
                return $.extend(data, $('.ocform').serializeJSON());
            }
        },
        "rowCallback": function (row, data) {
            selected.push(data.ID);

            botones(data.DT_RowId, $(row).find('td .opts'), data);
        },
        "drawCallback": function (settings) {
            var api = this.api();
            selected = [];
            $.each(api.rows().data(), function () {
                selected.push(this.ID);
            })
        },
        "language": {
            "processing": "Procesando...",
            "lengthMenu": "Mostrar _MENU_ por página",
            "search": "Buscar:",
            "zeroRecords": "No se encontraron datos",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "infoFiltered": "(filtrado de _MAX_ registros totales)"
        },
        "searching": false,
        "pageLength": 10,
        "columns": cols,
        "columnDefs": [{
            "targets": [1],
            "visible": true,
            "searchable": true
        }]
    });

    $('.ocform input').DTFilter($table);

    $('.ocform input,.ocform select').change(function () {
        $table.draw();
        return false;
    })

});