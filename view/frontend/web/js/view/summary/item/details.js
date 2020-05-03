/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

define([
    'uiComponent'
], function (Component) {
    'use strict';

    const {quoteItemData} = window.checkoutConfig;

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
        },

        /**
         * @param {Object} quoteItem
         * @returns {String}
         */
        getItemDeliveryTime: function(quoteItem) {
            const itemProduct = this.getItemProduct(quoteItem.item_id);
            return itemProduct.delivery_time;
        },

        /**
         * @param {Number} item_id
         * @returns {Object}
         */
        getItemProduct: function(item_id) {
            let itemElement = null;
            _.each(quoteItemData, function(element) {
                if (element.item_id === item_id.toString()) {
                    itemElement = element;
                }
            });
            return itemElement;
        }
    });
});
