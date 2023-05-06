(function ($) {
    $.fn.cursoPeriodo = function (options) {
        var _options = $.extend({
            byCategoria: false,
            byDocente: false,
            changeGrupo: $.noop
        }, options);

        this.each(function () {

            let select_categoria = $('#selectCategoria');

            var curso_url = _options.byDocente ? 'producto/s2ByDocente' : 'producto/s2',
                grupo_url = _options.byDocente ? 'Grupos/getByPeriodo' : 'Grupos/getByPeriodo';

            var $this = $(this),
                template = $('#cursoPeriodo').tmpl(),
                select_curso = template.find('select[name="curso_id"]'),
                select_periodo = template.find('select[name="periodo_id"]'),
                select_grupo = template.find('select[name="grupo_id"]');


            select_categoria.mySelect2({
                url: baseurl + '/areaAcademica/s2GetCategorias',
                onSelect: selectCategoria,
                minimumInputLength: 0,
                placeholder: 'Seleccione una categoria'
            });



            function selectCategoria() {

                let categoria = $("#selectCategoria option:selected").val();

                select_curso.mySelect2({
                    url: baseurl + curso_url + '/' + categoria,
                    onSelect: selectCurso,
                    minimumInputLength: 0,
                    placeholder: 'Ingrese el curso'
                });
            }

            select_curso.mySelect2({
                url: baseurl + curso_url,
                onSelect: selectCurso,
                minimumInputLength: 0,
                placeholder: 'Ingrese el curso'
            });

            select_periodo.change(changePeriodo);
            select_grupo.change(_options.changeGrupo);

            template.appendTo($this);

            function selectCurso(e) {
                var select_val = $(e.currentTarget).val();

                $.ajax({
                    url: baseurl + 'Periodo/getByCurso/' + select_val,
                    dataType: 'json',
                    success: function (response) {
                        select_periodo.empty();
                        var $option = $('<option>');
                        $option.attr('value', '');
                        $option.text('Seleccione el periodo');
                        select_periodo.append($option);

                        $.each(response.data, function (key, value) {
                            var $option = $('<option>');
                            $option.attr('value', value.id);
                            $option.text(value.label);
                            select_periodo.append($option);
                        });
                    }
                });
            }

            function changePeriodo(e, f, g) {
                var select_val = $(e.currentTarget).val();

                $.ajax({
                    url: baseurl + grupo_url + '/' + select_val,
                    dataType: 'json',
                    success: function (response) {
                        select_grupo.empty();
                        var $option = $('<option>');
                        $option.attr('value', '');
                        $option.text('Seleccione el grupo');
                        select_grupo.append($option);

                        $.each(response.data, function (key, value) {
                            var $option = $('<option>');
                            $option.attr('value', value.id);
                            $option.text(value.label);
                            select_grupo.append($option);
                        });
                    }
                });
            }
        });
    }
})(jQuery);