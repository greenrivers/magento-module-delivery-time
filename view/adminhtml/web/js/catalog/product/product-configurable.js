/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

define([
    'jquery',
], function ($) {
    'use strict';

    $(document).ready(function () {
        setTimeout(function () {
            $(document).on('change', '[name="product[delivery_time_from_simple]"]', function() {
                $('[data-index="type"], [data-index="delivery_time_inherit"], [data-index="delivery_time_product_simple"]').toggle();

                const checkbox = $(this);
                const radio = $('[data-index="type"] input:last');
                toggleRange(checkbox, radio);
            });

            $(document).on('change', '[name="product[delivery_time_inherit]"]', function() {
                $('[data-index="delivery_time_from_simple"]').toggle();
            });
        }, 7000);

        /**
         * @param {Object} checkbox
         * @param {Object} radio
         */
        function toggleRange(checkbox, radio) {
            if (parseInt(checkbox.val()) === 1) {
                $('[data-index="range"]').hide();
            } else if (!radio.prop('checked')) {
                $('[data-index="range"]').show();
            }
        }
    });
});
