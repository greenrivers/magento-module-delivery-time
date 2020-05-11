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
use Unexpected\DeliveryTime\Helper\Render;

class DefaultItem
{
    /** @var Config */
    private $config;

    /** @var Render */
    private $render;

    /** @var Request */
    private $request;

    /**
     * DefaultItem constructor.
     * @param Config $config
     * @param Render $render
     * @param Request $request
     */
    public function __construct(Config $config, Render $render, Request $request)
    {
        $this->render = $render;
        $this->config = $config;
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
        $product = $item->getProduct();
        if ($this->render->canShowOnProduct($layout, $product)) {
            $result['label_delivery_time'] = $this->render->getLabel();
            $result['delivery_time'] = $this->render->getFromProduct($product);
        }
        return $result;
    }
}
