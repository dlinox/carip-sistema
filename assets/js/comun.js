var getScript = jQuery.getScript;
jQuery.getScriptA = function (resources, callback) {
    var scripts = [];

    if (typeof (resources) === 'string') { scripts.push(resources) } else { scripts = resources; }

    var length = scripts.length,
        handler = function () { counter++; },
        deferreds = [],
        counter = 0,
        idx = 0;

    $.ajaxSetup({ async: false });
    for (; idx < length; idx++) {
        deferreds.push(
            getScript(scripts[idx], handler)
        );
    }

    jQuery.when.apply(null, deferreds).then(function () {
        callback();
    });
};
/**evaluar si no se repiten*/
var _cf = (function () {
    function _shift(x) {
        var parts = x.toString().split('.');
        return (parts.length < 2) ? 1 : Math.pow(15, parts[1].length);
    }
    return function () {
        return Array.prototype.reduce.call(arguments, function (prev, next) { return prev === undefined || next === undefined ? undefined : Math.max(prev, _shift(next)); }, -Infinity);
    };
})();

Math.a = function () {
    var f = _cf.apply(null, arguments); if (f === undefined) return undefined;
    function cb(x, y, i, o) { return x + f * y; }
    return Array.prototype.reduce.call(arguments, cb, 0) / f;
};

Math.s = function (l, r) { var f = _cf(l, r); return (l * f - r * f) / f; };

Math.m = function () {
    var f = _cf.apply(null, arguments);
    function cb(x, y, i, o) { return (x * f) * (y * f) / (f * f); }
    return Array.prototype.reduce.call(arguments, cb, 1);
};

Math.d = function (l, r) { var f = _cf(l, r); return (l * f) / (r * f); };
/****************/
function esNumeroPositivo(value) {
    var patron = /^\d+(\.\d+)?$/,
        bool = patron.test(value);
    return bool;
}
function myRound(response) {
    response = parseFloat(response).toFixed(3);
    response = parseFloat(response).toFixed(2);
    return response;
}
(function ($) {
    $.fn.serializeJSON = function (obj) {
        var json = {};
        if (typeof (obj) != 'undefined')
            for (var k in obj)
                json[obj[k]] = [];
        $.each($(this).serializeArray(), function () {
            if (typeof (json[this.name]) == 'undefined')
                json[this.name] = this.value;
            else if (typeof (json[this.name]) == 'object')
                json[this.name].push(this.value);
        });
        return json;
    };

    $.fn.nextFocus = function () {
        $(this).bind("keydown", function (e) {
            var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
            if (key == 13) {
                e.preventDefault();
                var inputs = $(this).closest('form').find(':input[type=text]:visible');
                inputs.eq(inputs.index(this) + 1).focus();
            }
        });
    }
})(jQuery);
(function ($) {

    var url = window.location;
    // for single sidebar menu
    $('ul.nav-sidebar a').filter(function () {
        return this.href == url;
    }).addClass('active');

    // for sidebar menu and treeview
    $('ul.nav-treeview a').filter(function () {
        return this.href == url;
    }).parentsUntil(".nav-sidebar > .nav-treeview")
        .css({'display': 'block'})
        .addClass('menu-open').prev('a')
        .addClass('active');

    $.fn.load_dialog = function (config) {
        var $contenedor;
        if (config.content !== undefined)
            $contenedor = config.content.appendTo($('body'));
        else
            $contenedor = $('<div class="modal fade" tabindex="-1">').appendTo($('body'));
        var set_dialog = function () {
            var ftmp = config.close;
            config.close = function () {
                if (ftmp !== undefined)
                    ftmp();
                $contenedor.remove();
            }
            $contenedor.find('.modal-title').text(config.title);
            $contenedor.modal({ 'show': true, backdrop: 'static' });
            $contenedor.on('hidden.bs.modal', function (e) {
                $contenedor.remove();
            })
            $.gs_loader.hide();
            if (config.loaded !== undefined)
                config.loaded($contenedor);
        }
        $.gs_loader.show();
        var url = $(this).attr('href');
        if (config.custom_url !== undefined)
            url = config.custom_url;
        if (url !== undefined) {
            $contenedor.load(url, config.data, function () {
                if (typeof (config.script) != 'undefined')
                    $.getScriptA(config.script, set_dialog);
                else
                    set_dialog();
            });
        } else {
            if (typeof (config.script) != 'undefined')
                $.getScriptA(config.script, set_dialog);
            else
                set_dialog();
        }
        return $contenedor;
    }
    $.fn.getSerial = function (diselse, array) {
        var serial;
        if (diselse) {
            var backup = [];
            $(':disabled[name]', this).each(function () {
                $(this).attr("disabled", false);
                backup.push($(this));
            });
            if (typeof (array) == 'undefined') {
                serial = this.serialize();  //serializar form
                console.log(this);
                console.log(serial);
            }
            else {
                serial = this.serializeArray();
            }

            $.each(backup, function () {
                this.attr("disabled", true);
            });
        } else {
            if (typeof (array) == 'undefined')
                serial = this.serialize();
            else
                serial = this.serializeArray();
        }
        return serial;
    }
    $.fn.dval = function (val) {
        if (typeof (val) == 'undefined') {
            return $(this).attr('dval')
        }
        $(this).val(myRound(val));
        $(this).attr('dval', val);
        return $(this);
    }
    $.fn.formPost = function (diselse, objdata, callbackfn) {
        if (typeof (objdata) == 'function') {
            callbackfn = objdata;
            objdata = {};
        }
        $.gs_loader.show();
        var serial = $(this).getSerial(diselse);
        serial = serial + '&' + $.param(objdata);

        $.post(this.attr('action'), serial, function (data) {
            $.gs_loader.hide();
            jsoneval_sData(data, callbackfn);
        }, 'html').fail(function (error) { $.gs_loader.hide(); alert(error.responseText.replace(/(<([^>]+)>)/ig, "")) });
        return false;
    }
    jQuery.fn.dialog = function (options) {

        var $contdiag = $(this);
        if (options == "close") {
            $contdiag.modal('hide');
            console.log($contdiag)
            return false;
        }
        $html = $contdiag.html();
        $contdiag = $(diag);
        $contdiag.appendTo($('body')).find('.modal-body').html($html);
        $contdiag.modal({ 'show': true, backdrop: 'static' });
        $contdiag.on('hidden.bs.modal', function (e) {
            $contdiag.remove();
        });
        return $contdiag;
    }

    $.fn.load_dataTable = function (config) {

        var $table = $(this);

        table_config = ({
            bFilter: false,
            processing: true,
            serverSide: true,
            bLengthChange: false,
            language: {
                processing: "Procesando...",
                lengthMenu: "Mostrar _MENU_ por página",
                search: "Buscar:",
                zeroRecords: "No se encontraron datos",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "No hay registros disponibles",
                paginate: {
                    first: "Primero",
                    last: "Ultimo",
                    next: "Siguiente",
                    previous: "Anterior"
                },
                infoFiltered: "(filtrado de _MAX_ registros totales)"
            },
            order: config.order,
            ajax: {
                url: config.url,
                type: "POST",
                data: function (data) {
                    return $.extend(data, $('' + config.cactions).serializeJSON());
                }
            },
            columns: config.columns
        });
        var table = $table.DataTable(table_config);
        return table;
    }

})(jQuery);
function IsJsonStr(str) {
    try {
        var result = $.parseJSON(str);
    } catch (e) {
        return false;
    }
    return result;
}

function jsoneval_sData(data, callbackfn, eval) {
    if (!(data && (data = IsJsonStr(data)))) {
        data = {};
        data.mensaje = 'Error al recuperar datos del servidor';
        data.exito = false;
    }
    if (typeof callbackfn == 'function') {
        callbackfn.call(this, data);
    }
}

var aa = '<div class="modal fade" tabindex="-1">'
    + '<div class="modal-dialog modal-sm" role="document">'
    + '  <div class="modal-content">'
    + '      <div class="modal-header">'
    + '          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
    + '          <h4 class="modal-title" id="myModalLabel">Alerta</h4>'
    + '      </div>'
    + '      <div class="modal-body">'
    + '          ...'
    + '      </div>'
    + '      <div class="modal-footer">'
    + '          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'
    + '      </div>'
    + '  </div>'
    + '</div>'
    + '</div>';

var ab = '<div class="modal fade" tabindex="-1">'
    + '<div class="modal-dialog modal-sm" role="document">'
    + '  <div class="modal-content">'
    + '      <div class="modal-header">'
    + '          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
    + '          <h4 class="modal-title" id="myModalLabel">Confirmar</h4>'
    + '      </div>'
    + '      <div class="modal-body">'
    + '          ...'
    + '      </div>'
    + '      <div class="modal-footer">'
    + '          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>'
    + '          <button type="button" class="btn btn-primary">Aceptar</button>'
    + '      </div>'
    + '  </div>'
    + '</div>'
    + '</div>';


$.alert = function (content) {
    var $container = $(aa);
    $container.find('.modal-body').html(content);
    $container.appendTo($('body')).find('.modal-body').html(content);
    $container.modal({ 'show': true, backdrop: 'static' });
    $container.on('hidden.bs.modal', function (e) {
        $container.remove();
    })
}
$.confirm = function (content, accept, cancel) {
    var $container = $(ab);
    $container.find('.modal-body').html(content);
    $container.appendTo($('body')).find('.modal-body').html(content);
    $container.modal({ 'show': true, backdrop: 'static' });
    $container.on('hidden.bs.modal', function (e) {
        $container.remove();
    })

    $container.find('.btn-primary').click(function () {
        $container.on('hidden.bs.modal', function (e) {
            if (typeof (accept) == 'function')
                accept();
        })
        $container.modal('hide');
    });
}

$(function () {
    $.gs_loader = $('<div>').hide();
    $.gs_loader.append($('<div>', {
        'class': 'ui-widget-overlay',
        'style': 'z-index:9998'
    })).append = ($('<div>').html('<img src="' + $("#baseurl").val() + 'assets/img/cubo-loader.gif"/>').css({
        'position': 'fixed',
        'font': 'bold 12px Verdana, Arial, Helvetica, sans-serif',
        'left': '50%',
        'top': '50%',
        'z-index': '9999',
        'margin-left': '-32px',
        'margin-top': '-32px'
    })).appendTo($.gs_loader);
    $.gs_loader.appendTo($('body'));
});
function replaceAll(str, find, replace) {
    return str.replace(new RegExp(find, 'g'), replace);
}

(function ($) {


    var selected = [];
    $.fn.reset_selected = function () {
        selected = [];
    }

    $.fn.DTFilter = function (table) {
        oTimerId = null;
        $(this).keyup(function () {
            window.clearTimeout(oTimerId);
            oTimerId = window.setTimeout(function () {
                table.draw();
            }, 500);
        });
    }

    $.fn.load_simpleTable = function (config, buton, bot) {
        var $table = $(this);
        var wch = $table.attr('wch');

        var cols = Array();
        if (buton == true) {
            console.log('IF');
            if (wch) {
                cols.push({
                    "data": null,
                    "orderable": false,
                    "width": "30",
                    'render': function (data, type, full, meta) {
                        if (typeof (bot) === "undefined") {
                            return '<input type="checkbox">';
                        } else {
                            return bot;
                        }
                    }
                })
            }
        }
        else {
            console.log('ELSE');
            $visible = [0];
        }

        $table.find('tr .ths').each(function (i, item) {
            cols.push({ "data": $(item).text(), className: "edit" });
        })

        var table_config = {
            dom: "<'row'<'col-sm-6 col-lg-8'B><'col-sm-6col-lg-4 text-right'lf>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                {
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
            "order": config.order,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todo"]],
            "ajax": {
                "url": config.data_source,
                "type": "POST",
                "data": function (data) {
                    return $.extend(data, $('' + config.cactions).serializeJSON());
                }
            },

            "rowCallback": function (row, data) {
                if (wch) {

                    if ($.inArray(data.DT_RowId, selected) !== -1) {
                        $(row).addClass('selected').find('input[type=checkbox]').prop("checked", true);
                    }
                    if (data.DT_Estado) {
                        switch (data.DT_Estado) {
                            case "2": $class = "bg-success"; break;
                            case "3": $class = "bg-danger"; break;
                            case "4": $class = "bg-warning"; break;
                            default: $class = ""; break;
                        }
                        $(row).addClass($class);
                    }
                    if (data.DT_Estadon) {
                        if (data.DT_Estadon == "03" || data.DT_Estadon == "04") $class = "bg-success";
                        else if (data.DT_Estadon == "05" || data.DT_Estadon == "10" || data.DT_Estadon == "11" || data.DT_Estadon == "12") $class = "bg-danger";
                        else if (data.DT_Estadon == "06" || data.DT_Estadon == "07") $class = "bg-warning";
                        else $class = "";
                        console.log(data.DT_Estadon);
                        $(row).addClass($class);
                    }

                    $(row).find('input[type=checkbox]').change(function (e, a) {
                        var index = $.inArray(data.DT_RowId, selected);
                        if (index === -1) {
                            selected.push(data.DT_RowId);
                        } else {
                            selected.splice(index, 1);
                        }
                        $(row).toggleClass('selected');
                        config.oncheck.call(this, row, data, selected);
                        e.preventDefault();
                        e.stopPropagation();
                    })
                    if ($.inArray(data.DT_RowId, selected) !== -1) {
                        $(row).addClass('selected').find('input[type=checkbox]').prop("checked", true);
                    }
                }
                if (typeof config.onrow == 'function') {
                    config.onrow.call(this, row, data, selected);
                }
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
            "columnDefs": [
                {
                    "targets": [1],
                    "visible": true,
                    "searchable": true
                }
            ]
        };
        var table = $table.DataTable(table_config)
        return table;
    }

})(jQuery);