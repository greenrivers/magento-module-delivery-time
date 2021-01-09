/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

const config = {
    config: {
        mixins: {
            'Magento_Swatches/js/swatch-renderer': {
                'Greenrivers_DeliveryTime/js/catalog/product/swatch-renderer': true
            }
        }
    },
    map: {
        '*': {
            sliderRange: 'Greenrivers_DeliveryTime/js/layer/slider-range'
        }
    }
};
