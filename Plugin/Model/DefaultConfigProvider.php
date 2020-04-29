<?php

namespace Unexpected\DeliveryTime\Plugin\Model;

use Magento\Checkout\Model\DefaultConfigProvider as Subject;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Helper\Render;

class DefaultConfigProvider
{
    /** @var Config */
    private $config;

    /** @var Render */
    private $render;

    /**
     * DefaultConfigProvider constructor.
     * @param Config $config
     * @param Render $render
     */
    public function __construct(Config $config, Render $render)
    {
        $this->config = $config;
        $this->render = $render;
    }

    /**
     * @param Subject $subject
     * @param array $result
     * @return array
     */
    public function afterGetConfig(Subject $subject, array $result): array
    {
        $items = $result['totalsData']['items'];
        for ($i = 0; $i < count($items); $i++) {
            $product = $result['quoteItemData'][$i]['product'];
            $items[$i]['delivery_time'] = $this->render->getFromProductArray($product);
        }
        $result['totalsData']['items'] = $items;
        return $result;
    }
}
