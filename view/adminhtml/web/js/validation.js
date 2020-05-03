/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

define([
    'jquery',
    'mage/translate',
    'jquery/validate'
], function ($) {
    'use strict';

    return function () {
        $.validator.addMethod(
            'validate-max-scale',

            /**
             * @param {String} value
             * @returns {Boolean}
             */
            function (value) {
                const min = parseInt($('#delivery_time_backend_min_scale').val());
                return parseInt(value) > min;
            },
            $.mage.__('Please enter a number greater than min scale.')
        );

        $.validator.addMethod(
            'validate-scale-step',

            /**
             * @param {String} value
             * @returns {Boolean}
             */
            function (value) {
                const min = parseInt($('#delivery_time_backend_min_scale').val());
                const max = parseInt($('#delivery_time_backend_max_scale').val());
                return parseInt(value) <= max - min;
            },
            $.mage.__('Please enter a number lower than difference between max and min scale.')
        );
    }
});
