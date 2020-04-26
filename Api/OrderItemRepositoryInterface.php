<?php

namespace Unexpected\DeliveryTime\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Unexpected\DeliveryTime\Api\Data\OrderItemInterface;

interface OrderItemRepositoryInterface
{
    /**
     * @param OrderItemInterface $orderItem
     * @return OrderItemInterface
     * @throws CouldNotSaveException
     */
    public function save(OrderItemInterface $orderItem): OrderItemInterface;

    /**
     * @param int $deliveryTimeId
     * @return OrderItemInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $deliveryTimeId): OrderItemInterface;
}
