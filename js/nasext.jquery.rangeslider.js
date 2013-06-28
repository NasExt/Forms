$(document).ready(function () {

    $('.range-slider').each(function () {
        var $init = {
            range: true
        };

        var $systemSetup = $(this).data('setup');
        var $setup = {
            min: $systemSetup.min,
            max: $systemSetup.max,
            values: [ $systemSetup.minValue, $systemSetup.maxValue ],
            slide: function (event, ui) {
                $('#' + $systemSetup.minId).val(ui.values[ 0 ]);
                $('#' + $systemSetup.maxId).val(ui.values[ 1 ]);
            }
        };
        $.extend($init, $setup);

        $(this).find('#' + $systemSetup.rangeSliderId).slider($init);

        $('#' + $systemSetup.minId).on("change", function (e) {
            e.preventDefault();
            if ($.isNumeric($(this).val())) {
                $('#' + $systemSetup.rangeSliderId).slider("values", 0, $(this).val());
            }
        });

        $('#' + $systemSetup.maxId).on("change", function (e) {
            e.preventDefault();
            if ($.isNumeric($(this).val())) {
                $('#' + $systemSetup.rangeSliderId).slider("values", 1, $(this).val());
            }
        });
    });

});
