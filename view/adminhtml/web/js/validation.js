define([
    'jquery',
    'mage/translate',
    'jquery/validate'
], function ($) {
    'use strict';

    return function () {
        $.validator.addMethod(
            'validate-max-scale', function (value) {
                const min = parseInt($('#delivery_time_general_min_scale').val());
                return parseInt(value) > min;
            },
            $.mage.__('Please enter a number greater than min scale.')
        );

        $.validator.addMethod(
            'validate-scale-step', function (value) {
                const min = parseInt($('#delivery_time_general_min_scale').val());
                const max = parseInt($('#delivery_time_general_max_scale').val());
                return parseInt(value) <= max - min;
            },
            $.mage.__('Please enter a number lower than difference between max and min scale.')
        );
    }
});
