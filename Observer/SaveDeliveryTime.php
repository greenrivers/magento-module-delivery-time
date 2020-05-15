<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Observer;

use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Sales\Model\Order\Item;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;
use Unexpected\DeliveryTime\Api\DeliveryTimeRepositoryInterface;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Helper\Render;
use Unexpected\DeliveryTime\Model\DeliveryTime;
use Unexpected\DeliveryTime\Model\DeliveryTimeFactory;

class SaveDeliveryTime implements ObserverInterface
{
    /** @var DeliveryTimeFactory */
    private $deliveryTimeFactory;

    /** @var DeliveryTimeRepositoryInterface */
    private $deliveryTimeRepository;

    /** @var Config */
    private $config;

    /** @var Render */
    private $render;

    /** @var LoggerInterface */
    private $logger;

    /**
     * SaveDeliveryTime constructor.
     * @param DeliveryTimeFactory $deliveryTimeFactory
     * @param DeliveryTimeRepositoryInterface $deliveryTimeRepository
     * @param Config $config
     * @param Render $render
     * @param LoggerInterface $logger
     */
    public function __construct(
        DeliveryTimeFactory $deliveryTimeFactory,
        DeliveryTimeRepositoryInterface $deliveryTimeRepository,
        Config $config,
        Render $render,
        LoggerInterface $logger
    ) {
        $this->deliveryTimeFactory = $deliveryTimeFactory;
        $this->deliveryTimeRepository = $deliveryTimeRepository;
        $this->config = $config;
        $this->render = $render;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        if ($this->config->getEnableConfig()) {
            /** @var Order $order */
            $order = $observer->getOrder();
            $items = $order->getAllVisibleItems();

            foreach ($items as $item) {
                $product = $this->getProduct($item);
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

    /**
     * @param Item $item
     * @return Product
     */
    private function getProduct(Item $item): Product
    {
        return $item->getProductType() === Configurable::TYPE_CODE ?
            $item->getChildrenItems()[0]->getProduct() : $item->getProduct();
    }
}
