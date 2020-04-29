<?php

namespace Unexpected\DeliveryTime\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;
use Unexpected\DeliveryTime\Api\DeliveryTimeRepositoryInterface;
use Unexpected\DeliveryTime\Helper\Render;
use Unexpected\DeliveryTime\Model\DeliveryTime;
use Unexpected\DeliveryTime\Model\DeliveryTimeFactory;

class SaveDeliveryTime implements ObserverInterface
{
    /** @var DeliveryTimeFactory */
    private $deliveryTimeFactory;

    /** @var DeliveryTimeRepositoryInterface */
    private $deliveryTimeRepository;

    /** @var Render */
    private $render;

    /** @var LoggerInterface */
    private $logger;

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
            $content = $this->render->getFromProduct($product);

            /** @var DeliveryTime $deliveryTime */
            $deliveryTime = $this->deliveryTimeFactory->create();
            $deliveryTime->setOrderItemId($item->getItemId())
                ->setContent($content);
            try {
                $this->deliveryTimeRepository->save($deliveryTime);
            } catch (CouldNotSaveException $e) {
                $this->logger->error($e->getMessage());
            }
        }
    }
}
