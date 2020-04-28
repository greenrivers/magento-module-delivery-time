<?php

namespace Unexpected\DeliveryTime\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Sales\Model\Order;
use Unexpected\DeliveryTime\Api\DeliveryTimeRepositoryInterface;
use Unexpected\DeliveryTime\Helper\Render;
use Unexpected\DeliveryTime\Model\DeliveryTime;
use Unexpected\DeliveryTime\Model\DeliveryTimeFactory;

class SaveOrderItem implements ObserverInterface
{
    /** @var DeliveryTimeFactory */
    private $deliveryTimeFactory;

    /** @var DeliveryTimeRepositoryInterface */
    private $deliveryTimeRepository;

    /** @var Render */
    private $view;

    /**
     * SaveOrderItem constructor.
     * @param DeliveryTimeFactory $deliveryTimeFactory
     * @param DeliveryTimeRepositoryInterface $deliveryTimeRepository
     * @param Render $view
     */
    public function __construct(
        DeliveryTimeFactory $deliveryTimeFactory,
        DeliveryTimeRepositoryInterface $deliveryTimeRepository,
        Render $view
    ) {
        $this->deliveryTimeFactory = $deliveryTimeFactory;
        $this->deliveryTimeRepository = $deliveryTimeRepository;
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
            $content = $this->view->renderFromProduct($product);
            /** @var DeliveryTime $deliveryTime */
            $deliveryTime = $this->deliveryTimeFactory->create();
            $deliveryTime->setOrderItemId($item->getItemId())
                ->setContent($content);
            try {
                $this->deliveryTimeRepository->save($deliveryTime);
            } catch (CouldNotSaveException $e) {
            }
        }
    }
}
