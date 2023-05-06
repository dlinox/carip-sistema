(function($)
{
    console.log('asdas');
    var methods = 
    {
        init: init, 
        set: set
    };

    function init(options)
    {
        console.log('init');
        var config = $.extend(
        {
            onSelect: $.noop,
        }, options);

        return this.each(function()
        {

            var $this = $(this);
            $this.mySelect2({url: baseurl + 'usuario/s2getByTipo/5', onSelect: config.onSelect, minimumInputLength: 0});
        });
    }

    function set(id)
    {
        this.each(function()
        {
            var $this = $(this);

            $.ajax({url: baseurl + "usuario/getById/" + id}).then(function(data)
            {
                var option = new Option(data.usua_nombres + ' ' + data.usua_apellidos + ' (' + data.usua_dni + ')', data.usua_id, true, true);
                $this.append(option).trigger('change');

                $this.trigger({type: 'select2:select'});
            });
        });
    }

    $.fn.s2docente = function(methodOrOptions)
    {
        if(methods[methodOrOptions])
        {
            return methods[methodOrOptions].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if(typeof methodOrOptions === 'object' || !methodOrOptions)
        {
            return methods.init.apply(this, arguments);
        }
        else { $.error('Method ' +  methodOrOptions + ' does not exist on s2docente'); }
    }
}
)(jQuery);