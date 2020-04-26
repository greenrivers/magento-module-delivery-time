<?php

namespace Unexpected\DeliveryTime\Plugin;

use Magento\Framework\DataObject;
use Magento\Sales\Block\Adminhtml\Order\View\Items\Renderer\DefaultRenderer as Subject;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Helper\View;

class DefaultRenderer
{
    /** @var Config */
    private $config;

    /** @var View */
    private $view;

    /**
     * DefaultRenderer constructor.
     * @param Config $config
     * @param View $view
     */
    public function __construct(Config $config, View $view)
    {
        $this->config = $config;
        $this->view = $view;
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
        if ($column === 'delivery-time') {
            $result = $this->view->renderFromOrderItem($item);
        }
        return $result;
    }

    public function afterGetColumns(Subject $subject, array $result): array
    {
        if ($this->config->getEnableConfig()) {
            $result = $this->insertArrayAtPosition($result, ['delivery-time' => 'col-delivery-time'], 4);
        }
        return $result;
    }

    function insertArrayAtPosition($array, $insert, $position)
    {
        return array_slice($array, 0, $position, TRUE) + $insert + array_slice($array, $position, NULL, TRUE);
    }
}
