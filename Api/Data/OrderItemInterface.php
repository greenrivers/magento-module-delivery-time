<?php

namespace Unexpected\DeliveryTime\Api\Data;

interface OrderItemInterface
{
    const DELIVERY_TIME_ID = 'delivery_time_id';
    const ORDER_ITEM_ID = 'order_item_id';
    const DELIVERY_TIME = 'delivery_time';

    /**
     * @return int
     */
    public function getDeliveryTimeId(): int;

    /**
     * @param int $deliveryTimeId
     * @return $this
     */
    public function setDeliveryTimeId(int $deliveryTimeId): self;

    /**
     * @return int
     */
    public function getOrderItemId(): int;

    /**
     * @param int $orderItemId
     * @return $this
     */
    public function setOrderItemId(int $orderItemId): self;

    /**
     * @return string
     */
    public function getDeliveryTime(): string;

    /**
     * @param string $deliveryTime
     * @return $this
     */
    public function setDeliveryTime(string $deliveryTime): self;
}
