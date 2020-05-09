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
     * @param string $layout
     * @param Product $product
     * @return bool
     */
    public function canShowOnProduct(string $layout, Product $product): bool
    {
        return $this->render->canShowOnProduct($layout, $product);
    }

    /**
     * @param string $layout
     * @param array $items
     * @return bool
     */
    public function canShowOnProducts(string $layout, array $items): bool
    {
        return $this->render->canShowOnProducts($layout, $items);
    }

    /**
     * @param string $layout
     * @param array $items
     * @return bool
     */
    public function canShowOnItems(string $layout, array $items): bool
    {
        return $this->render->canShowOnItems($layout, $items);
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
