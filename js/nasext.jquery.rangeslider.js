/**
 * This file is part of the NasExt extensions of Nette Framework
 *
 * Copyright (c) 20013 Dusan Hudak (http://dusan-hudak.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

$(document).ready(function () {

    $('.range-slider-control').each(function () {
        var $init = {
            range: true
        };

        var $systemSetup = $(this).data('init');
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
        if ($(this).data('customInit')) {
            $.extend($init, $(this).data('customInit'));
        }

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
