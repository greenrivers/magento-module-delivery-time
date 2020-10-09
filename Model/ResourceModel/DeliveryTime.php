<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_DeliveryTime
 */

namespace GreenRivers\DeliveryTime\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use GreenRivers\DeliveryTime\Api\Data\DeliveryTimeInterface;

class DeliveryTime extends AbstractDb
{
    const DELIVERY_TIME_TABLE = 'GreenRivers_delivery_time';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::DELIVERY_TIME_TABLE, DeliveryTimeInterface::DELIVERY_TIME_ID);
    }
}
