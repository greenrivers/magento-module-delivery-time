<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\CustomerData;

use Magento\Checkout\CustomerData\DefaultItem as Subject;
use Magento\Framework\App\Request\Http as Request;
use Magento\Quote\Model\Quote\Item;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Helper\ProductType;
use Unexpected\DeliveryTime\Helper\Render;

class DefaultItem
{
    /** @var Config */
    private $config;

    /** @var Render */
    private $render;

    /** @var ProductType */
    private $productType;

    /** @var Request */
    private $request;

    /**
     * DefaultItem constructor.
     * @param Config $config
     * @param Render $render
     * @param ProductType $productType
     * @param Request $request
     */
    public function __construct(Config $config, Render $render, ProductType $productType, Request $request)
    {
        $this->config = $config;
        $this->render = $render;
        $this->productType = $productType;
        $this->request = $request;
    }

    /**
     * @param Subject $subject
     * @param array $result
     * @param Item $item
     * @return array
     */
    public function afterGetItemData(Subject $subject, array $result, Item $item): array
    {
        $layout = $this->request->getFullActionName();
        $product = $this->productType->getProductFromQuoteItem($item);
        if ($this->render->canShowOnProduct($layout, $product)) {
            $result['label_delivery_time'] = $this->render->getLabel();
            $result['delivery_time'] = $this->render->getFromProduct($product);
        }
        return $result;
    }
}
