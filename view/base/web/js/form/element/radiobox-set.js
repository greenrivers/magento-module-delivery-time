/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_DeliveryTime
 */

define([
    'Magento_Ui/js/form/element/checkbox-set',
    'uiRegistry'
], function (Component, registry) {
    'use strict';

    return Component.extend({
        /**
         * @inheritdoc
         */
        initConfig: function () {
            this._super();

            const deliveryTimeType = registry.get('index = delivery_time_type');

            this.value = deliveryTimeType.value();
            this.value = this.normalizeData(this.value);

            return this;
        },

        /**
         * @inheritdoc
         */
        hasChanged: function () {
            const value = this.value();
            const initial = this.initialValue;
            const range = registry.get('index = range');

            range.setConfig(parseInt(value));

            return this.multiple ?
                !utils.equalArrays(value, initial) :
                this._super();
        }
    });
});
