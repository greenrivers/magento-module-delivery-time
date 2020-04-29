/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

define([
    'uiComponent'
], function (Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Unexpected_DeliveryTime/summary/item/details'
        },

        /**
         * @param {Object} quoteItem
         * @returns {String}
         */
        getValue: function (quoteItem) {
            return quoteItem.name;
        }
    });
});
