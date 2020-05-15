/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

define([
    'jquery'
], function ($) {
    'use strict';

    return function (widget) {
        $.widget('mage.SwatchRenderer', widget, {
            /**
             * @inheritdoc
             */
            _OnClick: function ($this, $widget) {
                this._super($this, $widget);

                const {deliveryTime} = this.options.jsonConfig;
                this.updateDeliveryTime(deliveryTime);
            },

            /**
             * @param {Array} deliveryTime
             */
            updateDeliveryTime: function (deliveryTime) {
                const index = this.getProduct() ?? 'configurable';
                $('.delivery_time .value').html(deliveryTime[index]);
            }
        });

        return $.mage.SwatchRenderer;
    }
});
