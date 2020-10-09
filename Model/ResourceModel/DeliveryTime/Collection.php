<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_DeliveryTime
 */

namespace GreenRivers\DeliveryTime\Model\ResourceModel\DeliveryTime;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use GreenRivers\DeliveryTime\Model\DeliveryTime;
use GreenRivers\DeliveryTime\Model\ResourceModel\DeliveryTime as DeliveryTimeResource;

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
