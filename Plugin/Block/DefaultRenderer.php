<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\Block;

use Magento\Framework\DataObject;
use Magento\Sales\Block\Adminhtml\Order\View\Items\Renderer\DefaultRenderer as Subject;
use Unexpected\DeliveryTime\Helper\OrderView;
use Unexpected\DeliveryTime\Helper\Render;

class DefaultRenderer
{
    /** @var Render */
    private $render;

    /** @var OrderView */
    private $orderView;

    /**
     * DefaultRenderer constructor.
     * @param Render $render
     * @param OrderView $orderView
     */
    public function __construct(Render $render, OrderView $orderView)
    {
        $this->render = $render;
        $this->orderView = $orderView;
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
        if ($column === OrderView::DELIVERY_TIME_COLUMN) {
            $result = $this->render->getFromOrderItem($item);
        }
        return $result;
    }

    public function afterGetColumns(Subject $subject, array $result): array
    {
        return $this->orderView->addColumn(
            $result,
            [OrderView::DELIVERY_TIME_COLUMN => 'col-delivery-time'],
            OrderView::POSITION
        );
    }
}
