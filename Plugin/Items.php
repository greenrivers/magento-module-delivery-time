<?php

namespace Unexpected\DeliveryTime\Plugin;

use Magento\Sales\Block\Adminhtml\Order\View\Items as Subject;
use Unexpected\DeliveryTime\Helper\Config;

class Items
{
    /** @var Config */
    private $config;

    /**
     * Items constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function afterGetColumns(Subject $subject, array $result): array
    {
        if ($this->config->getEnableConfig()) {
            $result = $this->insertArrayAtPosition($result, ['delivery-time' => 'Delivery Time'], 4);
        }
        return $result;
    }

    function insertArrayAtPosition($array, $insert, $position)
    {
        return array_slice($array, 0, $position, TRUE) + $insert + array_slice($array, $position, NULL, TRUE);
    }
}
