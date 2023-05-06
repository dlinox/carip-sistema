var url = "";
var $table, $table_asesor;
var baseurl;
let fecha = $('#mes_pagar').val();
$(document).ready(function () {
    baseurl = $("#baseurl").val();
    var url = nameurl + '?json=true';
    var idpersona = $('#idpersona').val();

    $table = $('#mitabla').DataTable({
        "processing": true,
        "serverSide": true,
        "bResetDisplay": true,
        "order": [
            [1, "desc"]
        ],
        "ajax": {
            "url": url,
            "type": "POST",
        },
        "language": {
            "processing": "Procesando...",
        },
        "searching": false,
        "pageLength": 10,
        "columnDefs": [{
            "targets": [1],
            "visible": true,
            "searchable": false
        }]
    });

    $('#mes_pagar').change(function () {
        fecha = $('#mes_pagar').val();
        totales(idpersona, fecha);
        return false;
    });

    totales(idpersona, fecha);
    $('form#form-1').submit(function () {
        $.gs_loader.show();
        $(this).formPost(true, {}, function (data) {
            $.gs_loader.hide();
            if (data.exito) {
                swal("", data.mensaje, "success");
                let url = baseurl + 'Contabilidad/pagopersonal';
                let url_comprobante = baseurl + 'Contabilidad/pagoPersonalComprobanteA4/' + data.pago_id;

                setTimeout(function () {
                    $(location).attr('href', url);
                }, 3000);

                setTimeout(function () {
                    window.open(url_comprobante, '_blank');
                }, 1000);

                $('.error-message-paquete').addClass('hidden');

            } else {
                console.log(data);
                // swal("", data.mensaje, "error");
                $('.error-message-paquete').html(data.mensaje);
                $('.error-message-paquete').removeClass('hidden');
            }
        });
        return false;
    });

    $('form#form-2').submit(function () {

        $.gs_loader.show();
        $(this).formPost(true, {}, function (data) {
            $.gs_loader.hide();

            if (data.exito) {
                swal("", data.mensaje, "success");
                setTimeout(function () {
                    $(location).attr('href', $('#baseurl').val() + 'Contabilidad/reportepagos');
                }, 1000);
                $('.error-message-paquete').addClass('hidden');

            } else {
                // swal("", data.mensaje, "error");
                $('.error-message-paquete').html(data.mensaje);
                $('.error-message-paquete').removeClass('hidden');
            }
        });
        return false;
    });



});

const horas_dictado =  document.getElementsByClassName('horas-cant');
const costos_hora = document.getElementsByClassName('costo-hora');
const total_dictado =  document.getElementsByClassName('curso-total');

for (let index = 0; index < horas_dictado.length; index++) {
    horas_dictado[index].addEventListener("change", function (e) {
        totalHorasCosto(e, index)   
    });
    costos_hora[index].addEventListener("change", function (e) {
        totalHorasCosto(e, index)   
    });
}

function totalHorasCosto(e, index){
    total_dictado[index].value =  parseFloat(horas_dictado[index].value) * parseFloat(costos_hora[index].value);
    sum();
}



function sum() {

    //par el rol de docente 

    let docente_cursos  = 0.00;
    for (let index = 0; index < total_dictado.length; index++) {
        docente_cursos =  parseInt(docente_cursos) + parseInt(total_dictado[index].value);
    }
    let monto = document.getElementById('monto').value;
    let bono = document.getElementById('bono[]').value;
    let comisiondirecta = document.getElementById('comisiondirecta[]').value;
    let comisionasesores = document.getElementById('comisionasesores[]').value;

    //let horas = document.getElementById('horas[]').value;
    //let costohora = document.getElementById('costohora[]').value;
    //document.getElementById('sumaDocente[]').value = horas * costohora;

    let adelanto = document.getElementById('adelanto').value;
    var descuento = document.getElementById('descuento').value;

    if (comisiondirecta == null) {
        comisiondirecta = 0;
    }
    if (comisionasesores == null) {
        comisionasesores = 0;
    }

    var resultado = parseInt(monto) + parseInt(bono) + parseInt(comisiondirecta) + parseInt(comisionasesores);// + parseInt(horas) * parseInt(costohora);

    resultado = resultado - parseInt(descuento) - parseInt(adelanto);

    if (isNaN(resultado)) {
        resultado = 0;
    }

    if (!isNaN(resultado)) {

        resultado = parseInt(docente_cursos) + parseInt(resultado);
        document.getElementById('total').value = resultado;
    }
}

function totales(idpersona, fecha) {
    $.ajax({
        dataType: "json",
        method: "POST",
        url: baseurl + "Contabilidad/getcomisiones/" + idpersona,
        data: {
            mes: fecha
        },
        success: function (resp) {
            console.log(resp);
            let directo_total = parseFloat(resp.directo.total),
                asesorado_total = parseFloat(resp.asesorado.total),
                descuento_total = parseFloat(resp.descuento.total),
                adelanto_total = parseFloat(resp.adelanto.total);
            document.getElementById('comisiondirecta[]').value = directo_total.toFixed(2);
            document.getElementById('comisionasesores[]').value = asesorado_total.toFixed(2);
            document.getElementById('descuento').value = descuento_total.toFixed(2);
            document.getElementById('adelanto').value = adelanto_total.toFixed(2);
            sum();
        }
    });
}