(function($){

    var methods = 
    {
        init: init, 
        set: set, 
        getSelect: getSelect, 
        setExcept: setExcept
    };

    function init(options)
    {
        console.log('f-init');
        var config = $.extend({
                show_first_buscador: true, 
                name: 'persona', 
                onSelect: $.noop, 
                id: 0, 
                prefix: '',
                
            }, options), 
            id = '#s2persona', 
            template = $(id).tmpl(), 
            tmpl_config = { name: config.name, prefix: config.prefix };

        if(template.length)
        {
            return this.each(function()
            {
                var $this = $(this), 
                    template = $('#s2persona').tmpl(tmpl_config), 
                    btn_show_hide = template.find('.s2p-btn-show-hide'), 
                    cb_agregar = template.find('input[name="agregar_' + config.name + '"]'), 
                    box_nuevo = template.siblings('.s2p-box-nuevo'), 
                    box_buscador = template.siblings('.s2p-box-buscador'), 
                    select = template.find('select[name="' + config.name + '_id"]');

                $this.data('select', select);

                if(config.show_first_buscador)
                {
                    box_buscador.show();
                    box_nuevo.hide();
                    btn_show_hide.text('Agregar nuevo');
                    cb_agregar.prop('checked', false);
                }
                else
                {
                    box_buscador.hide();
                    box_nuevo.show();
                    btn_show_hide.text('Mostrar bucador');
                    cb_agregar.prop('checked', true);
                }

                btn_show_hide.on('click', function()
                {
                    var value = !cb_agregar.prop('checked');
                    cb_agregar.prop('checked', value);
                    btn_show_hide.text(value ? 'Mostrar buscador' : 'Agregar nuevo');
                    box_nuevo.toggle('slow');
                    box_buscador.toggle('slow');
                });

                var s2_config = 
                {
                    placeholder: 'Ingrese DNI o nombres del estudiante',
                    allowClear: true,
                    width: '100%',
                    language: "es",
                    ajax: 
                    {
                        url: baseurl + "persona/s2",
                        dataType: 'json',
                        delay: 444, 
                        data: function (params)  { return params; },
                        processResults: function (data, params) 
                        {
                            var _except = $this.data('except'), 
                                except = _except ? _except.val() : 0;
                            
                            params.page = params.page || 1;
                            return {
                                results: except ? $.map(data.items, function (obj) {
                                    if(obj.id == except) { return null; }
                                    else { return obj; }
                                }) : data.items,
                                pagination: {
                                    more: (params.page * 30) < data.total_count
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; },
                    minimumInputLength: 2,
                };

                select.select2(s2_config);
                select.on('select2:select', config.onSelect);
                template.appendTo(this);
            });
        }
        else { console.error('No se encontro el template con id: ' + id); }
    }

    function set(id)
    {
 
        this.each(function()
        {
            var $this = $(this);
            if($this.data('select'))
            {   
                $.ajax({url: baseurl + "persona/getById/" + id, }).then(function(data)
                {
                    var option = new Option(data.pers_nombres + ' ' + data.pers_apellidos + ' (' + data.pers_dni + ')', data.pers_id, true, true);
                    $this.data('select').append(option).trigger('change');
                });
            }
            else { console.error('Debe de iniciar el plugin primero'); }
        });
    }

    function getSelect()
    {
   
        var $this = $(this);
        if($this.data('select')) {  return $this.data('select'); }
        else { console.error('Debe de iniciar el plugin primero'); }
    }

    function setExcept(except)
    {
   
        var $this = $(this);
        if($this.data('select')) { $this.data('except', except); }
        else { console.error('Debe de iniciar el plugin primero'); }
    }

    $.fn.s2persona = function(methodOrOptions)
    {

        if( methods[methodOrOptions])
        {
            return methods[methodOrOptions].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if(typeof methodOrOptions === 'object' || !methodOrOptions)
        {
            return methods.init.apply(this, arguments);
        }
        else { $.error('Method ' +  methodOrOptions + ' does not exist on s2persona'); }
    }
})(jQuery);