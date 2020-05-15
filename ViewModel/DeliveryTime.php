<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\ViewModel;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Model\Order\Item;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Helper\ProductType;
use Unexpected\DeliveryTime\Helper\Render;

class DeliveryTime implements ArgumentInterface
{
    /** @var Config */
    private $config;

    /** @var Render */
    private $render;

    /** @var ProductType */
    private $productType;

    /**
     * DeliveryTime constructor.
     * @param Config $config
     * @param Render $render
     * @param ProductType $productType
     */
    public function __construct(Config $config, Render $render, ProductType $productType)
    {
        $this->config = $config;
        $this->render = $render;
        $this->productType = $productType;
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
     * @return string
     */
    public function getLabel(): string
    {
        return $this->render->getLabel();
    }

    /**
     * @param QuoteItem $item
     * @return Product
     */
    public function getProductFromQuoteItem(QuoteItem $item): Product
    {
        return $this->productType->getProductFromQuoteItem($item);
    }

    /**
     * @param Item $item
     * @return Product
     */
    public function getProductFromItem(Item $item): Product
    {
        return $this->productType->getProductFromItem($item);
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
