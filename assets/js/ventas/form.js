var url = "";
var $table;
$(document).ready(function () {
    var _producto_id = $('#_producto_id').val();

    $("#apoderado").hide();
    $('select[name="condiciones_id"]').change(function () {
        if ($('select[name="condiciones_id"]').val() == 2) {
            $("#apoderado").show();
        } else {
            $("#apoderado").hide();
        }
    });

    let select_categoria = $('#selectCategoria');
    select_categoria.mySelect2({
        url: baseurl + '/areaAcademica/s2GetCategorias',
        onSelect: selectCategoria,
        minimumInputLength: 0,
        placeholder: 'Seleccione una categoria'
    });

    function selectCategoria(e) {
        let select_val = $(e.currentTarget).val();
        $('select[name="productos_id"]').s2producto({
            onSelect: selectProducto,
            categoria: select_val
        });
    }

    $('select[name="productos_id"]').s2producto({
        onSelect: selectProducto
    });

    if (_producto_id) {
        $('select[name="productos_id"]').s2producto('set', _producto_id);
    }

    function selectProducto(e) {
        var select_val = $(e.currentTarget).val();
        console.log(select_val);

        if (select_val) {
            $.ajax({
                url: $('#baseurl').val() + 'producto/getById/' + select_val,
                dataType: 'json',
                success: function (response) {
                    $('input[name="cuotas"]').val(response.cuotas);
                    $('input[name="costo"]').val(response.costo);
                }
            });
        } else {
            $('input[name="cuotas"]').val('');
        }
    }


    $('form#form-1').submit(function () {
        $.gs_loader.show();
        $(this).formPost(true, {}, function (data) {
            $.gs_loader.hide();

            if (data.exito) {
                swal("", data.mensaje, "success");
                setTimeout(function () {
                    $(location).attr('href', $('#baseurl').val() + 'Ventas/lista');
                }, 1000);
                $('.error-message-paquete').addClass('hidden');

            } else {
                swal("", data.mensaje, "error");
                $('.error-message-paquete').html(data.mensaje);
                $('.error-message-paquete').removeClass('hidden');
            }
        });
        return false;
    });

    $('#s2-persona').s2persona();
    $('#s2-apoderado').s2persona({
        name: 'apoderado',
        prefix: 'titular_'
    });

    $('#s2-persona').s2persona('setExcept', $('#s2-apoderado').s2persona('getSelect'));
    $('#s2-apoderado').s2persona('setExcept', $('#s2-persona').s2persona('getSelect'));

    var _persona_id = $('#_persona_id').val();
    var _apoderado_id = $('#_apoderado_id').val();

    if (_persona_id) {

        $('#s2-persona').s2persona('set', _persona_id);
    }

    if (_apoderado_id) {
        $('#condiciones_id').change();
        $('#s2-apoderado').s2persona('set', _apoderado_id);
    }

    $('#esBecado').on('change', changeEsBecado);

    function changeEsBecado() {
        // var $this = $(e.currentTarget);
        var $this = $('#esBecado');
        if (($this).is(':checked')) {
            $('#caja-descuento').addClass('hidden');
        } else {
            $('#caja-descuento').removeClass('hidden');
        }
    }

    $('#tieneDescuento').on('change', changeTieneDescuento);

    function changeTieneDescuento() {
        // var $this = $(e.currentTarget);
        var $this = $('#tieneDescuento');
        if (($this).is(':checked')) {
            $('#caja-input-descuento').removeClass('hidden');
        } else {
            $('#caja-input-descuento').addClass('hidden');
        }
    }


    let selectPersona = $('select[name="persona_id"]')

    selectPersona.change(function () {

        let selectRubro = $('select[name="rubros_id"]');
        selectRubro.attr('disabled', false);
        let selectTipoAlumno = $('select[name="tipo_alumnos_id"]');
        selectTipoAlumno.attr('disabled', false);
        let selectCondicion = $('select[name="condiciones_id"]');
        selectCondicion.attr('disabled', false);

        if (this.value != '') {
            $.ajax({
                url: baseurl + 'persona/getPersona/' + this.value,
                dataType: 'json',
                success: function (data) {

                    if (data.exito) {
                        if (data.datos.rubros_id) {
                            selectRubro.val(data.datos.rubros_id);
                            selectRubro.attr('disabled', true);
                        }
                        if (data.datos.tipo_alumnos_id) {
                            selectTipoAlumno.val(data.datos.tipo_alumnos_id);
                            selectTipoAlumno.attr('disabled', true);
                        }

                        if (data.datos.condiciones_id) {
                            selectCondicion.val(data.datos.condiciones_id);
                            selectCondicion.attr('disabled', true);
                        }

                    }
                }
            });
        }

    });


    function _init() {
        changeEsBecado();
        changeTieneDescuento();
    }

    _init();
});


$('.select2-results__option').click(function () {
    console.log('sdfsdf');
});

function imprimir() {

    var doc = new jsPDF();
    //var e = document.getElementById("nombreproducto");
    //var strUser = e.value;

    /** Nombre Curso */
    var producto = document.getElementById("nombreproducto");
    var curso = producto.options[producto.selectedIndex].text;
    //alert(curso);
    console.log(curso);

    /** Cuotas y Costo */

    var cuotas = document.getElementById("numcuotas");
    console.log(cuotas);

    /** Persona */
    //var persona = document.getElementsByName("persona_id");
    //var per = persona.options[persona.selectedIndex].text;

    //var per = $('').val;
    var _persona_id = $('#_persona_id').val();

    console.log(_persona_id);

    //alert(curso);
    //console.log("nombreas:::::"+persona);

    //console.log("ne"+per);



    /** Imagen */
    baseurl = $("#baseurl").val();
    console.log(baseurl);




    /** Fecha */
    var d = new Date();
    var strDate = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();
    var time = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
    /** */


    var doc = new jsPDF()



    //doc.text(20, 20, strUser)
    /*
        doc.setFont('courier')
        doc.setFontType('normal')
        doc.text(20, 30, strDate)
    
        doc.setFont('times')
        doc.setFontType('italic')
        doc.text(20, 40, 'This is times italic.')
    
        doc.setFont('helvetica')
        doc.setFontType('bold')
        doc.text(20, 50, 'This is helvetica bold.')
    
        doc.setFont('courier')
        doc.setFontType('bolditalic')
        doc.text(20, 60, 'This is courier bolditalic.')
    */
    /**asfasfasfa */
    doc.setFontSize(12)
    doc.setFont("courier", "normal");
    doc.text("CARIP PERU", 20, 20);
    doc.text("15/04/2021", 190, 20, null, null, "right");

    doc.setFontSize(16)
    doc.setFont("times", "normal");

    doc.text("FICHA DE MATRICULA", 105, 30, null, null, "center");
    doc.setLineWidth(0.1)
    //doc.line(20, 22, 190, 22) // horizontal line

    doc.setFontSize(15)
    doc.text("DATOS DEL ALUMNO:", 20, 40);
    doc.line(20, 45, 190, 45) // horizontal line

    doc.rect(20, 45, 170, 6)
    doc.rect(20, 51, 170, 10)

    doc.setFont("times", "normal");
    doc.setFontSize(12)
    doc.text("Nombres y Apellidos:", 25, 49);

    doc.setFont("times", "normal");
    doc.setFontSize(14)
    doc.text("ALEX DAVID CHAMBI CORI", 25, 58);


    doc.setFont("times", "normal");
    doc.text("This is centred text.", 105, 80, null, null, "center");
    doc.text("And a little bit more underneath it.", 105, 90, null, null, "center");





    doc.output('save', 'filename.pdf'); //Try to save PDF as a file (not works on ie before 10, and some mobile devices)

    //doc.save('test.pdf');
    //doc.output('dataurlnewwindow');
}

function getDatosSunat() {
    let dni = $('#input-dni').val();

  
    
    if(!isNaN(dni) && dni.length == 8){
        $.gs_loader.show();
        $.ajax({
            url: baseurl + 'Persona/getPersonaApiSunat/' + dni,
            dataType: 'json',
            success: function (resp) {
                if(resp.success){
                    let nombres = resp.data.nombres;
                    let apellidos = resp.data.apellido_paterno + ' '+ resp.data.apellido_materno;
                    $('#input-nombre').val(nombres);
                    $('#input-apellidos').val(apellidos);
                }
                else{
                    alert('No se puedo encontrar registros para el dni, o paso la cantidad de consultas mensuales (200)');
                }
            },
            complete: () =>{
                $.gs_loader.hide();
            }
        });
    }
    else{
        alert('Error, intente mas tarde')
        $.gs_loader.hide();
    }

}