/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_DeliveryTime
 */

const config = {
    config: {
        mixins: {
            'Magento_Swatches/js/swatch-renderer': {
                'GreenRivers_DeliveryTime/js/catalog/product/swatch-renderer': true
            }
        }
    },
    map: {
        '*': {
            sliderRange: 'GreenRivers_DeliveryTime/js/layer/slider-range'
        }
    }
};
