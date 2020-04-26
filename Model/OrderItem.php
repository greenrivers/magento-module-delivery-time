<?php

namespace Unexpected\DeliveryTime\Model;

use Magento\Framework\Model\AbstractModel;
use Unexpected\DeliveryTime\Api\Data\OrderItemInterface;
use Unexpected\DeliveryTime\Model\ResourceModel\OrderItem as OrderItemResource;

class OrderItem extends AbstractModel implements OrderItemInterface
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(OrderItemResource::class);
        parent::_construct();
    }

    /**
     * @inheritDoc
     */
    public function getDeliveryTimeId(): int
    {
        return (int)$this->_getData(OrderItemInterface::DELIVERY_TIME_ID);
    }

    /**
     * @inheritDoc
     */
    public function setDeliveryTimeId(int $deliveryTimeId): OrderItemInterface
    {
        $this->setData(OrderItemInterface::DELIVERY_TIME_ID, (int)$deliveryTimeId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOrderItemId(): int
    {
        return (int)$this->_getData(OrderItemInterface::ORDER_ITEM_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOrderItemId(int $orderItemId): OrderItemInterface
    {
        $this->setData(OrderItemInterface::ORDER_ITEM_ID, (int)$orderItemId);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDeliveryTime(): string
    {
        return $this->_getData(OrderItemInterface::DELIVERY_TIME);
    }

    /**
     * @inheritDoc
     */
    public function setDeliveryTime(string $deliveryTime): OrderItemInterface
    {
        $this->setData(OrderItemInterface::DELIVERY_TIME, $deliveryTime);
        return $this;
    }
}
