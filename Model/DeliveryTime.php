<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Model;

use Magento\Framework\Model\AbstractModel;
use Greenrivers\DeliveryTime\Api\Data\DeliveryTimeInterface;
use Greenrivers\DeliveryTime\Model\ResourceModel\DeliveryTime as DeliveryTimeResource;

class DeliveryTime extends AbstractModel implements DeliveryTimeInterface
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(DeliveryTimeResource::class);
        parent::_construct();
    }

    /**
     * @inheritDoc
     */
    public function getDeliveryTimeId(): int
    {
        return $this->_getData(self::DELIVERY_TIME_ID);
    }

    /**
     * @inheritDoc
     */
    public function setDeliveryTimeId(int $deliveryTimeId): DeliveryTimeInterface
    {
        $this->setData(self::DELIVERY_TIME_ID, $deliveryTimeId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOrderItemId(): int
    {
        return $this->_getData(self::ORDER_ITEM_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOrderItemId(int $orderItemId): DeliveryTimeInterface
    {
        $this->setData(self::ORDER_ITEM_ID, $orderItemId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getContent(): string
    {
        return $this->_getData(self::CONTENT);
    }

    /**
     * @inheritDoc
     */
    public function setContent(string $content): DeliveryTimeInterface
    {
        $this->setData(self::CONTENT, $content);
        return $this;
    }
}
