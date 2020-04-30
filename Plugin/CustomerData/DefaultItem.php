<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\CustomerData;

use Magento\Checkout\CustomerData\DefaultItem as Subject;
use Magento\Quote\Model\Quote\Item;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Helper\Render;

class DefaultItem
{
    /** @var Config */
    private $config;

    /** @var Render */
    private $render;

    /**
     * DefaultItem constructor.
     * @param Config $config
     * @param Render $render
     */
    public function __construct(Config $config, Render $render)
    {
        $this->render = $render;
        $this->config = $config;
    }

    /**
     * @param Subject $subject
     * @param array $result
     * @param Item $item
     * @return array
     */
    public function afterGetItemData(Subject $subject, array $result, Item $item): array
    {
        if ($this->config->getEnableConfig()) {
            $product = $item->getProduct();
            $result['delivery_time'] = $this->render->getFromProduct($product);
        }
        return $result;
    }
}
