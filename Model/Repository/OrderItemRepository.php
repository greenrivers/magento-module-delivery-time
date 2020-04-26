<?php

namespace Unexpected\DeliveryTime\Model\Repository;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Unexpected\DeliveryTime\Api\Data\OrderItemInterface;
use Unexpected\DeliveryTime\Api\OrderItemRepositoryInterface;
use Unexpected\DeliveryTime\Model\OrderItem;
use Unexpected\DeliveryTime\Model\ResourceModel\OrderItem as OrderItemResource;
use Unexpected\DeliveryTime\Model\OrderItemFactory;

class OrderItemRepository implements OrderItemRepositoryInterface
{
    /** @var OrderItemFactory */
    private $orderItemFactory;

    /** @var OrderItemResource */
    private $orderItemResource;

    /** @var array */
    private $orderItems;

    /**
     * OrderItemRepository constructor.
     * @param OrderItemFactory $orderItemFactory
     * @param OrderItemResource $orderItemResource
     */
    public function __construct(OrderItemFactory $orderItemFactory, OrderItemResource $orderItemResource)
    {
        $this->orderItemFactory = $orderItemFactory;
        $this->orderItemResource = $orderItemResource;
    }

    /**
     * @inheritDoc
     */
    public function save(OrderItemInterface $orderItem): OrderItemInterface
    {
        try {
            if ($orderItem->getDeliveryTimeId()) {
                $orderItem = $this->getById($orderItem->getDeliveryTimeId())->addData($orderItem->getData());
            }

            $this->orderItemResource->save($orderItem);
            unset($this->orderItems[$orderItem->getDeliveryTimeId()]);
        } catch (\Exception $e) {
            if ($orderItem->getDeliveryTimeId()) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save order item with ID %1. Error: %2',
                        [$orderItem->getDeliveryTimeId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotSaveException(__('Unable to save new order item. Error: %1', $e->getMessage()));
        }

        return $orderItem;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $deliveryTimeId): OrderItemInterface
    {
        if (!isset($this->orderItems[$deliveryTimeId])) {
            /** @var OrderItem $orderItem */
            $orderItem = $this->orderItemFactory->create();
            $this->orderItemResource->load($orderItem, $deliveryTimeId);
            if (!$orderItem->getDeliveryTimeId()) {
                throw new NoSuchEntityException(__('Order item with specified ID "%1" not found.', $deliveryTimeId));
            }
            $this->orderItems[$deliveryTimeId] = $orderItem;
        }

        return $this->orderItems[$deliveryTimeId];

    }
}
