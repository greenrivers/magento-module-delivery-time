<?php

/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\Block;

use Magento\Sales\Block\Adminhtml\Order\View\Items as Subject;
use Unexpected\DeliveryTime\Helper\OrderView;

class Items
{
    /** @var OrderView */
    private $orderView;

    /**
     * Items constructor.
     * @param OrderView $orderView
     */
    public function __construct(OrderView $orderView)
    {
        $this->orderView = $orderView;
    }

    /**
     * @param Subject $subject
     * @param array $result
     * @return array
     */
    public function afterGetColumns(Subject $subject, array $result): array
    {
        return $this->orderView->addColumn($result, [OrderView::DELIVERY_TIME_COLUMN => 'Delivery Time'], 4);
    }
}
