/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

define([
    'uiRegistry',
    'ko',
    'jquery',
    'Magento_Ui/js/form/element/abstract',
    'Magento_Ui/js/lib/knockout/template/renderer',
    'Magento_Ui/js/form/element/abstract',
    'mage/translate'
], function (registry, ko, $, AbstractElement, renderer) {
    'use strict';

    const sliderFn = 'slider';
    const deliveryTimeMin = registry.get('index = delivery_time_min');
    const deliveryTimeMax = registry.get('index = delivery_time_max');

    ko.bindingHandlers.sliderRange = {
        init: function (element, valueAccessor) {
            const config = valueAccessor();
            const {range, value, values} = config;

            if (range) {
                _.extend(config, {
                    values: [values[0](), values[1]()],

                    slide: function (event, ui) {
                        values[0](ui.values[0]);
                        values[1](ui.values[1]);
                        deliveryTimeMin.value(ui.values[0]);
                        deliveryTimeMax.value(ui.values[1]);
                    }
                });
            } else {
                _.extend(config, {
                    value: value(),

                    slide: function (event, ui) {
                        value(ui.value);
                        const deliveryTimeType = registry.get('index = delivery_time_type');
                        if (deliveryTimeType.value() === 1) {
                            deliveryTimeMin.value(ui.value);
                        } else {
                            deliveryTimeMax.value(ui.value);
                        }
                    }
                });
            }

            $(element)[sliderFn](config);
        }
    };

    renderer.addAttribute('sliderRange');

    return AbstractElement.extend({
        defaults: {
            elementTmpl: 'Unexpected_DeliveryTime/form/range',
            minScale: 1,
            maxScale: 100,
            scaleStep: 1,
            scale: 1,
            value: ko.observable(1),
            values: [ko.observable(1), ko.observable(100)],
            isRange: ko.observable(false),
            range: true,
            dateUnit: null
        },

        /**
         * @inheritdoc
         */
        initialize: function (config) {
            this._super();
            const {dateUnit, minScale, maxScale, scaleStep} = config.slider;
            this.dateUnit = dateUnit;
            this.minScale = parseInt(minScale);
            this.maxScale = parseInt(maxScale);
            this.scaleStep = parseInt(scaleStep);
            this.values[0](deliveryTimeMin.value() || this.minScale);
            this.values[1](deliveryTimeMax.value() || this.maxScale);
        },

        /**
         * @param {Number} type
         */
        setConfig: function (type) {
            this.isRange(type === 3);
            const deliveryTimeType = registry.get('index = delivery_time_type');
            deliveryTimeType.value(type);
        },

        /**
         * @returns {String}
         */
        getRange: function () {
            const {value, values, dateUnit} = this;
            const deliveryTimeType = registry.get('index = delivery_time_type');
            const typeValue = parseInt(deliveryTimeType.value());
            const minValue = deliveryTimeMin.value() || this.minScale;
            const maxValue = deliveryTimeMax.value() || this.maxScale;

            this.isRange(typeValue === 3);
            this.value(typeValue === 1 ? minValue : maxValue);

            if (!this.isRange()) {
                const text = typeValue === 2 ? 'From' : 'Up to';
                return $.mage.__(`${text} ${value()} ${dateUnit}`);
            }
            return $.mage.__(`From ${values[0]()} to ${values[1]()} ${dateUnit}`);
        }
    });
});
