<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Model\ResourceModel\DeliveryTime;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Greenrivers\DeliveryTime\Model\DeliveryTime;
use Greenrivers\DeliveryTime\Model\ResourceModel\DeliveryTime as DeliveryTimeResource;

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
