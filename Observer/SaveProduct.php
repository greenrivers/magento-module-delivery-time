<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Observer;

use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Api\LinkManagementInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class SaveProduct implements ObserverInterface
{
    /** @var LinkManagementInterface */
    private $linkManagement;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var LoggerInterface */
    private $logger;

    /**
     * SaveProduct constructor.
     * @param LinkManagementInterface $linkManagement
     * @param ProductRepositoryInterface $productRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        LinkManagementInterface $linkManagement,
        ProductRepositoryInterface $productRepository,
        LoggerInterface $logger
    ) {
        $this->linkManagement = $linkManagement;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        /** @var Product $product */
        $product = $observer->getProduct();
        if ($product->getTypeId() === Configurable::TYPE_CODE) {
            $inherit = $product->getDeliveryTimeInherit();
            $fromSimple = $product->getDeliveryTimeFromSimple();

            if ($inherit && !$fromSimple) {
                $sku = $product->getSku();
                $type = $product->getDeliveryTimeType();
                $min = $product->getDeliveryTimeMin();
                $max = $product->getDeliveryTimeMax();
                $childProducts = $this->linkManagement->getChildren($sku);
                foreach ($childProducts as $childProduct) {
                    if (!$childProduct->getDeliveryTimeType()) {
                        $childProduct->setDeliveryTimeType($type)
                            ->setDeliveryTimeMin($min)
                            ->setDeliveryTimeMax($max);
                        try {
                            $this->productRepository->save($childProduct);
                        } catch (Exception $e) {
                            $this->logger->error($e->getMessage());
                        }
                    }
                }
            }
        }
    }
}
