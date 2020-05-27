/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

const config = {
    config: {
        mixins: {
            'Magento_Swatches/js/swatch-renderer': {
                'Unexpected_DeliveryTime/js/catalog/product/swatch-renderer': true
            }
        }
    },
    map: {
        '*': {
            sliderRange: 'Unexpected_DeliveryTime/js/layer/slider-range'
        }
    }
};
