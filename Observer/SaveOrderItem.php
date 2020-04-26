<?php

namespace Unexpected\DeliveryTime\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Sales\Model\Order;
use Unexpected\DeliveryTime\Api\OrderItemRepositoryInterface;
use Unexpected\DeliveryTime\Helper\View;
use Unexpected\DeliveryTime\Model\OrderItem;
use Unexpected\DeliveryTime\Model\OrderItemFactory;

class SaveOrderItem implements ObserverInterface
{
    /** @var OrderItemFactory */
    private $orderItemFactory;

    /** @var OrderItemRepositoryInterface */
    private $orderItemRepository;

    /** @var View */
    private $view;

    /**
     * SaveOrderItem constructor.
     * @param OrderItemFactory $orderItemFactory
     * @param OrderItemRepositoryInterface $orderItemRepository
     * @param View $view
     */
    public function __construct(
        OrderItemFactory $orderItemFactory,
        OrderItemRepositoryInterface $orderItemRepository,
        View $view
    ) {
        $this->orderItemFactory = $orderItemFactory;
        $this->orderItemRepository = $orderItemRepository;
        $this->view = $view;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getOrder();
        $items = $order->getAllVisibleItems();
        foreach ($items as $item) {
            $product = $item->getProduct();
            $deliveryTime = $this->view->renderFromProduct($product);
            /** @var OrderItem $orderItem */
            $orderItem = $this->orderItemFactory->create();
            $orderItem->setOrderItemId($item->getItemId())
                ->setDeliveryTime($deliveryTime);
            try {
                $this->orderItemRepository->save($orderItem);
            } catch (CouldNotSaveException $e) {
            }
        }
    }
}
