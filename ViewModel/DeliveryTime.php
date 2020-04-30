<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\ViewModel;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Model\Order\Item;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Helper\Render;

class DeliveryTime implements ArgumentInterface
{
    /** @var Config */
    private $config;

    /** @var Render */
    private $render;

    /**
     * DeliveryTime constructor.
     * @param Config $config
     * @param Render $render
     */
    public function __construct(Config $config, Render $render)
    {
        $this->config = $config;
        $this->render = $render;
    }

    /**
     * @return int
     */
    public function isEnabled(): int
    {
        return $this->config->getEnableConfig();
    }

    /**
     * @param Product $product
     * @return string
     */
    public function renderFromProduct(Product $product): string
    {
        return $this->render->getFromProduct($product);
    }

    /**
     * @param Item $item
     * @return string
     */
    public function renderFromItem(Item $item): string
    {
        return $this->render->getFromOrderItem($item);
    }
}
