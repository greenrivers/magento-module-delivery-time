define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    return config => {
        const {divSlider, inputValues, aBtn, minValue, maxValue} = config;
        const sliderRange = $(divSlider);
        const values = $(inputValues);
        const btn = $(aBtn);

        sliderRange.slider({
            range: true,
            min: minValue,
            max: maxValue,
            values: [minValue, maxValue],
            slide: (event, ui) => {
                const [min, max] = ui.values;
                let href = btn.prop('href');

                values.val(`From ${min} - to ${max}`);
                href = href.replace(/delivery_time_min=\d+/g, `delivery_time_min=${min}`);
                href = href.replace(/delivery_time_max=\d+/g, `delivery_time_max=${max}`);
                btn.prop('href', href);
            }
        });

        const [min, max] = sliderRange.slider('values');
        values.val(`From ${min} - to ${max}`);
    }
});
