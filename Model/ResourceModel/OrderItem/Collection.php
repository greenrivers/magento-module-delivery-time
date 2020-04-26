<?php

namespace Unexpected\DeliveryTime\Model\ResourceModel\OrderItem;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Unexpected\DeliveryTime\Model\OrderItem;
use Unexpected\DeliveryTime\Model\ResourceModel\OrderItem as OrderItemResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = OrderItem::DELIVERY_TIME_ID;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(OrderItem::class, OrderItemResource::class);
        parent::_construct();
    }
}
