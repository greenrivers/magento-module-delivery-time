<?php

namespace Unexpected\DeliveryTime\Helper;

use Magento\Catalog\Model\Product;
use Magento\Sales\Model\Order\Item;

class View
{
    /** @var Config */
    private $config;

    /**
     * Render constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function renderFromProduct(Product $product): string
    {
        $type = $product->getDeliveryTimeType();
        $min = $product->getDeliveryTimeMinScale();
        $max = $product->getDeliveryTimeMaxScale();
        return $this->attributeParser($type, $min, $max);
    }

    public function renderFromOrderItem(Item $item): string
    {
        return '';
    }

    /**
     * @param int $type
     * @param int $min
     * @param int $max
     * @return string
     */
    public function attributeParser(int $type, int $min, int $max): string
    {
        $dateUnit = $this->config->getDateUnitConfig();

        switch ($type) {
            case 0:
                return __('From') . " {$min} {$dateUnit}";
            case 1:
                return __('From') . " {$max} {$dateUnit}";
            case 2:
                return __('From') . " {$min} {$dateUnit} " . __('To') . " {$max} {$dateUnit}";
            default:
                return '';
        }
    }

    /**
     * @param array $product
     * @return string
     */
    public function renderFromProductArray(array $product): string
    {
        $type = $product['delivery_time_type'];
        $min = $product['delivery_time_min_scale'];
        $max = $product['delivery_time_max_scale'];
        return $this->attributeParser($type, $min, $max);
    }
}
