define([
    'jquery',
    'ko',
    'uiComponent',
    'domReady!'
], function ($, ko, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Unexpected_DeliveryTime/system/config/checkbox',
            text: ko.observable('Yes'),
            isChecked: ko.observable(true)
        },

        initialize: function (config) {
            this._super();
            this.isChecked(config.isChecked);
        },

        initObservable: function () {
            this._super()
                .observe({
                    isChecked: ko.observable(true)
                });

            this.isChecked.subscribe(function (value) {
                this.text(value ? 'Yes' : 'No');
                this.toggleElements();
            }, this);

            return this;
        },

        toggleElements: function () {
            $('#row_delivery_time_general_date_unit').toggle();
            $('#row_delivery_time_general_min_scale').toggle();
            $('#row_delivery_time_general_max_scale').toggle();
            $('#row_delivery_time_general_scale_step').toggle();
        },

        getValue: function () {
            return this.isChecked() | 0;
        }
    });
});
