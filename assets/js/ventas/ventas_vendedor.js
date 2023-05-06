var url = "";
$(document).ready(function () {
    // $('#curso_id').mySelect2({url: baseurl + 'producto/s2', onSelect: selectCurso});

    let select_categoria = $('#selectCategoria');
    select_categoria.mySelect2({
        url: baseurl + '/areaAcademica/s2GetCategorias',
        onSelect: selectCategoria,
        minimumInputLength: 0,
        placeholder: 'Seleccione una categoria'
    });

    let select_vendedor = $('#selectVendedor');
    select_vendedor.mySelect2({
        url: baseurl + '/ventas/getVendedores',
        onSelect: () => { console.log('Vendedor Seleccionado'); },
        minimumInputLength: 0,
        placeholder: 'Seleccione un Vendedor'
    });

    $('#rango').myDateRangePicker();

    var _user_tipo_id = $('#_user_tipo_id').val(),
        ver_notas_class = _user_tipo_id == 4 ? 'hidden' : '',
        ver_asistencias_class = _user_tipo_id == 4 ? 'hidden' : '',
        agregar_pago_class = _user_tipo_id == 3 ? 'hidden' : '';



    var url = nameurl + '?json=true';
    var $table;



    function updateBarra(row, data) {
        let _pagado = data.PAGADO.split(" ", 2)[1];
        let _deuda = data.DEUDA.split(" ", 2)[1];
        //console.log(data.DT_CUOTAS);
        //console.log(data.PAGADO);
        //console.log(data.DEUDA);
        //console.log(data.DT_COSTO);

        var costocuota = data.DT_COSTO / data.DT_CUOTAS;
        var cuotaspagadas = _pagado / costocuota;
        //console.log("Pag:  ",cuotaspagadas);

        if (data.DT_es_becado == '1') {
            cuotaspagadas = data.DT_CUOTAS;
        }

        var iconos = "";

        for (let i = 0; i < data.DT_CUOTAS; i++) {

            if (cuotaspagadas > 0) {
                iconos = iconos + "<span class='pull-right badge bg-green'>-</span>";

                cuotaspagadas--;
            } else {
                iconos = iconos + "<span class='pull-right badge bg-red'>-</span>";
            }
        }

        //console.log("Iconos",iconos);

        var $icon = $(row).find('div.iconos'),
            $td = $icon.parent(),
            value = $icon.text();
        $td.empty();

        $td.html(iconos);



        var $barra = $(row).find('div.barra'),
            $td = $barra.parent(),
            value = $barra.text();
        $td.empty();
        if (data.DT_es_becado == '1') {
            $td.html('<span class="label label-info">BECADO</span>');
        } else {
            if (_deuda <= 0) {
                $td.html('<span class="label label-success">PAGO COMPLETO</span>');
            } else {
                $('#tmpl-barra-progreso').tmpl({
                    value: value
                }).appendTo($td);
            }
        }
    }

    var $dt = $('#mitabla'),
        conf = {
            data_source: url,
            cactions: ".ocform",
            order: [
                [0, "DESC"]
            ],
            pageLength: 50,
            lengthMenu: [20],
            onrow: function (row, data) {
                updateBarra(row, data);
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
        };


    $table = $dt.load_simpleTable(conf);

    $('.ocform select,.ocform input').change(function () {
        $table.draw();
        return false;
    });

    $('form').submit(function () {
        $.gs_loader.show();
        $(this).formPost(true, {}, function (data) {
            $.gs_loader.hide();
            if (data.exito) {
                console.log('AQIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII');
                swal("", data.mensaje, "success");
                $('.id_alumno').val(null).trigger('change');
                $table.draw();
                $('.error-message-paquete').addClass('hidden');
            } else {
                $('.error-message-paquete').html(data.mensaje);
                $('.error-message-paquete').removeClass('hidden');
            }
        });
        return false;
    });

    $("#buscar").keydown(function (e) {
        // Capturamos qué telca ha sido
        var keyCode = e.which;
        // Si la tecla es el Intro/Enter
        if (keyCode == 13) {
            // Evitamos que se ejecute eventos
            event.preventDefault();
            // Devolvemos falso
            alert("alex");
            return false;
        }
    });

});