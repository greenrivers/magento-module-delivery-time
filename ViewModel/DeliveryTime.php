<?php

namespace Unexpected\DeliveryTime\ViewModel;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Helper\View;

class DeliveryTime implements ArgumentInterface
{
    /** @var Config */
    private $config;

    /** @var View */
    private $view;

    /**
     * DeliveryTime constructor.
     * @param Config $config
     * @param View $view
     */
    public function __construct(Config $config, View $view)
    {
        $this->config = $config;
        $this->view = $view;
    }

    public function render(Product $product): string
    {
        $result = '';

        if ($this->config->getEnableConfig()) {
            $result = $this->view->renderFromProduct($product);
        }

        return $result;
    }

    public function renderItem()
    {
        return 'testowy test';
    }
}
