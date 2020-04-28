<?php

/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Unexpected\DeliveryTime\Api\Data\DeliveryTimeInterface;

class DeliveryTime extends AbstractDb
{
    const DELIVERY_TIME_TABLE = 'unexpected_delivery_time_order_item';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::DELIVERY_TIME_TABLE, DeliveryTimeInterface::DELIVERY_TIME_ID);
    }
}
