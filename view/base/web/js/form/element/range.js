define([
    'uiRegistry',
    'ko',
    'jquery',
    'Magento_Ui/js/form/element/abstract',
    'Magento_Ui/js/lib/knockout/template/renderer',
    'jquery-ui-modules/slider'
], function (registry, ko, $, AbstractElement, renderer) {
    'use strict';

    const sliderFn = 'slider';
    const deliveryTimeMinScale = registry.get('index=delivery_time_min_scale');
    const deliveryTimeMaxScale = registry.get('index=delivery_time_max_scale');

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
                        deliveryTimeMinScale.value(ui.values[0]);
                        deliveryTimeMaxScale.value(ui.values[1]);
                    }
                });
            } else {
                _.extend(config, {
                    value: value(),

                    slide: function (event, ui) {
                        value(ui.value);
                        const deliveryTimeType = registry.get('index=delivery_time_type');
                        if (deliveryTimeType.value() === 0) {
                            deliveryTimeMinScale.value(value());
                        } else {
                            deliveryTimeMaxScale.value(value());
                        }
                    }
                });
            }

            $(element)[sliderFn](config);
        },

        update: function (element, valueAccessor) {
            // const config = valueAccessor();

            // config.values = ko.unwrap(config.values);

            // $(element)[sliderFn]('option', config);
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
            value: ko.observable(20),
            values: [ko.observable(7), ko.observable(50)],
            isRange: ko.observable(true),
            range: true,
            dateUnit: null
        },

        initialize: function (config) {
            this._super();
            const {dateUnit, minScale, maxScale, scaleStep} = config.slider;
            this.dateUnit = dateUnit;
            this.minScale = parseInt(minScale);
            this.maxScale = parseInt(maxScale);
            this.scaleStep = parseInt(scaleStep);
            this.values[0](this.minScale);
            this.values[1](this.maxScale);
        },

        setConfig: function (type) {
            this.isRange(type === 2);
            const deliveryTimeType = registry.get('index=delivery_time_type');
            deliveryTimeType.value(type);
        },

        getRange: function () {
            const {value, values, dateUnit} = this;

            if (!this.isRange()) {
                return `Up to ${value()} ${dateUnit}`;
            }
            return `From ${values[0]()} to ${values[1]()} ${dateUnit}`;
        }
    });
});
