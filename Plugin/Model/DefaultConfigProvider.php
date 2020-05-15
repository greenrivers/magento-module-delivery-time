<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\Model;

use Magento\Catalog\Model\Product;
use Magento\Checkout\Model\DefaultConfigProvider as Subject;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Request\Http as Request;
use Magento\Quote\Model\Quote\Item;
use Unexpected\DeliveryTime\Helper\Render;

class DefaultConfigProvider
{
    /** @var Render */
    private $render;

    /** @var Request */
    private $request;

    /**
     * DefaultConfigProvider constructor.
     * @param Render $render
     * @param Request $request
     */
    public function __construct(Render $render, Request $request)
    {
        $this->render = $render;
        $this->request = $request;
    }

    /**
     * @param Subject $subject
     * @param array $result
     * @return array
     */
    public function afterGetConfig(Subject $subject, array $result): array
    {
        $layout = $this->request->getFullActionName();
        $items = $result['quoteData']['items'];
        $totalsDataItems = $result['totalsData']['items'];
        if ($this->render->canShowOnProducts($layout, $items)) {
            for ($i = 0; $i < count($items); $i++) {
                $item = $result['quoteData']['items'][$i];
                $product = $this->getProduct($item);
                $deliveryTime = $this->render->getFromProduct($product);
                $totalsDataItems[$i]['delivery_time'] = $result['quoteItemData'][$i]['delivery_time'] = $deliveryTime;
            }
            $result['totalsData']['items'] = $totalsDataItems;
            $result['quoteData']['label_delivery_time'] = $this->render->getLabel();
        }
        return $result;
    }

    /**
     * @param Item $item
     * @return Product
     */
    private function getProduct(Item $item): Product
    {
        return $item->getProductType() === Configurable::TYPE_CODE ?
            $item->getOptionByCode('simple_product')->getProduct() : $item->getProduct();
    }
}
