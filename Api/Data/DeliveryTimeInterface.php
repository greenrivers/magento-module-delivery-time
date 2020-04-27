<?php

/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Api\Data;

interface DeliveryTimeInterface
{
    const DELIVERY_TIME_ID = 'delivery_time_id';
    const ORDER_ITEM_ID = 'order_item_id';
    const CONTENT = 'content';

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
    public function getContent(): string;

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self;
}
