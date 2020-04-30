<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Model\ResourceModel\DeliveryTime;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Unexpected\DeliveryTime\Model\DeliveryTime;
use Unexpected\DeliveryTime\Model\ResourceModel\DeliveryTime as DeliveryTimeResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = DeliveryTime::DELIVERY_TIME_ID;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(DeliveryTime::class, DeliveryTimeResource::class);
        parent::_construct();
    }
}
