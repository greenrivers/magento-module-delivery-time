<?php

/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\Block;

use Magento\Framework\DataObject;
use Magento\Sales\Block\Adminhtml\Order\View\Items\Renderer\DefaultRenderer as Subject;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Helper\Render;

class DefaultRenderer
{
    const COLUMN = 'delivery-time';

    /** @var Config */
    private $config;

    /** @var Render */
    private $render;

    /**
     * DefaultRenderer constructor.
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
     * @param string $result
     * @param DataObject $item
     * @param string $column
     * @return string
     */
    public function afterGetColumnHtml(Subject $subject, string $result, DataObject $item, string $column): string
    {
        if ($column === self::COLUMN) {
            $result = $this->render->getFromOrderItem($item);
        }
        return $result;
    }

    public function afterGetColumns(Subject $subject, array $result): array
    {
        if ($this->config->getEnableConfig()) {
            $result = $this->insertArrayAtPosition($result, [self::COLUMN => 'col-delivery-time'], 4);
        }
        return $result;
    }

    function insertArrayAtPosition($array, $insert, $position)
    {
        return array_slice($array, 0, $position, TRUE) + $insert + array_slice($array, $position, NULL, TRUE);
    }
}
