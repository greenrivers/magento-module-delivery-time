<?php

namespace Unexpected\DeliveryTime\Plugin;

use Magento\Checkout\CustomerData\DefaultItem as Subject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Item;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Helper\View;

class MiniCart
{
    /** @var View */
    private $view;

    /** @var Config */
    private $config;

    /**
     * MiniCart constructor.
     * @param View $view
     * @param Config $config
     */
    public function __construct(View $view, Config $config)
    {
        $this->view = $view;
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
        try {
            if ($this->config->getEnableConfig()) {
                $product = $item->getProduct();
                $result['delivery_time'] = $this->view->renderFromProduct($product);
            }
        } catch (NoSuchEntityException $e) {
        }
        return $result;
    }
}
