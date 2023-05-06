(function ($) {
    var methods = {
        init: init,
        set: set
    };

    function init(options) {
        var config = $.extend({
            onSelect: $.noop,
            categoria: '',
        }, options);

        return this.each(function () {
            var $this = $(this);
            $this.mySelect2({
                url: baseurl + 'producto/s2' + '/' + config.categoria,
                onSelect: config.onSelect,
                minimumInputLength: 0,
                placeholder: 'Ingrese el termino de busqueda'
            });
        });
    }

    function set(id) {
        this.each(function () {
            var $this = $(this);

            $.ajax({
                url: baseurl + "producto/getById/" + id
            }).then(function (data) {
                var option = new Option(data.nombre, data.id, true, true);
                $this.append(option).trigger('change');
                $this.trigger({
                    type: 'select2:select'
                });
            });
        });
    }

    $.fn.s2producto = function (methodOrOptions) {
        if (methods[methodOrOptions]) {
            return methods[methodOrOptions].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof methodOrOptions === 'object' || !methodOrOptions) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + methodOrOptions + ' does not exist on s2produto');
        }
    }
})(jQuery);