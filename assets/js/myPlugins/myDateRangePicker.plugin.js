(function($)
{
    'use strict'
    $.fn.myDateRangePicker = function(options)
    {
        var config = $.extend({
            startDate: moment().subtract(29, 'days'), 
            endDate: moment(), 
            showDropdowns: true, 
            maxDate: new Date(), 
            locale: {
                applyLabel: "Aceptar",
                format: "DD/MM/YYYY",
                cancelLabel: 'Cancelar',
                customRangeLabel: 'Rango personalizado'
            },
            ranges: {
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Hoy': [moment(), moment()],
                'Semana actual': [moment().startOf('week'), moment()], 
                'Semana anterior': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')], 
                'Mes actual': [moment().startOf('month'), moment()], 
                'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')], 
                'Últimos 7 días': [moment().subtract(7, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(30, 'days'), moment()], 
            }
        }, options);

        this.each(function()
        {
            var $this = $(this);
            $this.daterangepicker(config);
        });
    };
}
)(jQuery);