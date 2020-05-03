<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\Block;

use Magento\Sales\Block\Adminhtml\Order\View\Items as Subject;
use Unexpected\DeliveryTime\Helper\OrderView;
use Unexpected\DeliveryTime\Helper\Render;

class Items
{
    /** @var OrderView */
    private $orderView;

    /** @var Render */
    private $render;

    /**
     * Items constructor.
     * @param OrderView $orderView
     * @param Render $render
     */
    public function __construct(OrderView $orderView, Render $render)
    {
        $this->orderView = $orderView;
        $this->render = $render;
    }

    /**
     * @param Subject $subject
     * @param array $result
     * @return array
     */
    public function afterGetColumns(Subject $subject, array $result): array
    {
        $layout = $subject->getRequest()->getFullActionName();
        if ($this->render->isEnabled($layout)) {
            $result = $this->orderView->addColumn(
                $result,
                [OrderView::DELIVERY_TIME_COLUMN => 'Delivery Time'],
                OrderView::POSITION
            );
        }
        return $result;
    }
}
