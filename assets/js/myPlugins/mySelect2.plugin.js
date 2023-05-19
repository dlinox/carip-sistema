var res_data = 'hola';
(function ($) {
    $.fn.mySelect2 = function (options) {
        var config = $.extend({
            url: '',
            minimumInputLength: 2,
            minimumResultsForSearch: 0,
            onSelect: $.noop
        }, options);

        this.each(function () {
            var $this = $(this);
            $this.select2({
                allowClear: true,
                placeholder: config.placeholder,
                width: '100%',
                language: "es",
                minimumInputLength: config.minimumInputLength,
                minimumResultsForSearch: config.minimumResultsForSearch,
                escapeMarkup: function (markup) {
                    return markup;
                },
                ajax: {
                    url: config.url,
                    dataType: 'json',
                    delay: 444,
                    data: function (params) {
                        return params;
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    }
                }
            });
            $this.on('select2:select', config.onSelect);
        });
    };
    return res_data;
})(jQuery);