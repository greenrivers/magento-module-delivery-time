define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    return config => {
        const {divSlider, inputValues, aBtn, maxValue, dateUnit} = config;
        const sliderRange = $(divSlider);
        const values = $(inputValues);
        const btn = $(aBtn);
        const urlParams = new URLSearchParams(location.search);
        const deliveryTimeMax = urlParams.get('delivery_time_max') ?? 1;

        sliderRange.slider({
            range: 'max',
            min: 1,
            max: maxValue + 1,
            value: 1,

            slide: (event, ui) => {
                let {value} = ui;
                let href = btn.prop('href');

                if (value === maxValue + 1) {
                    values.val('Undefined delivery time');
                    value = -1;
                } else {
                    values.val(`To ${value} ${dateUnit}`);
                }

                href = href.replace(/delivery_time_max=\d+/g, `delivery_time_max=${value}`);
                btn.prop('href', href);
            }
        });

        if (deliveryTimeMax === -1) {
            sliderRange.slider('value', maxValue + 1);
            values.val('Undefined delivery time');
        } else {
            sliderRange.slider('value', deliveryTimeMax);
            values.val(`To ${deliveryTimeMax} ${dateUnit}`);
        }
    }
});
