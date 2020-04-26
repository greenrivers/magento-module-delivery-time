<?php

namespace Unexpected\DeliveryTime\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Unexpected\DeliveryTime\Api\Data\OrderItemInterface;

class OrderItem extends AbstractDb
{
    const ORDER_ITEM_TABLE_NAME = 'unexpected_delivery_time_order_item';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::ORDER_ITEM_TABLE_NAME, OrderItemInterface::DELIVERY_TIME_ID);
    }
}
