<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;
use Greenrivers\DeliveryTime\Api\DeliveryTimeRepositoryInterface;
use Greenrivers\DeliveryTime\Helper\Config;
use Greenrivers\DeliveryTime\Helper\ProductType;
use Greenrivers\DeliveryTime\Helper\Render;
use Greenrivers\DeliveryTime\Model\DeliveryTime;
use Greenrivers\DeliveryTime\Model\DeliveryTimeFactory;

class SaveOrder implements ObserverInterface
{
    /** @var DeliveryTimeFactory */
    private $deliveryTimeFactory;

    /** @var DeliveryTimeRepositoryInterface */
    private $deliveryTimeRepository;

    /** @var Config */
    private $config;

    /** @var Render */
    private $render;

    /** @var ProductType */
    private $productType;

    /** @var LoggerInterface */
    private $logger;

    /**
     * SaveOrder constructor.
     * @param DeliveryTimeFactory $deliveryTimeFactory
     * @param DeliveryTimeRepositoryInterface $deliveryTimeRepository
     * @param Config $config
     * @param Render $render
     * @param ProductType $productType
     * @param LoggerInterface $logger
     */
    public function __construct(
        DeliveryTimeFactory $deliveryTimeFactory,
        DeliveryTimeRepositoryInterface $deliveryTimeRepository,
        Config $config,
        Render $render,
        ProductType $productType,
        LoggerInterface $logger
    ) {
        $this->deliveryTimeFactory = $deliveryTimeFactory;
        $this->deliveryTimeRepository = $deliveryTimeRepository;
        $this->config = $config;
        $this->render = $render;
        $this->productType = $productType;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        if ($this->config->getEnabledConfig()) {
            /** @var Order $order */
            $order = $observer->getOrder();
            $items = $order->getAllVisibleItems();

            foreach ($items as $item) {
                $product = $this->productType->getProductFromItem($item);
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
}
