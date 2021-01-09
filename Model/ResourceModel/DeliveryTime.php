<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Greenrivers\DeliveryTime\Api\Data\DeliveryTimeInterface;

class DeliveryTime extends AbstractDb
{
    const DELIVERY_TIME_TABLE = 'Greenrivers_delivery_time';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::DELIVERY_TIME_TABLE, DeliveryTimeInterface::DELIVERY_TIME_ID);
    }
}
