<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_DeliveryTime
 */

namespace GreenRivers\DeliveryTime\Observer;

use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Api\LinkManagementInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use GreenRivers\DeliveryTime\Setup\Patch\Data\AddDeliveryTimeAttributes;

class SaveProduct implements ObserverInterface
{
    /** @var LinkManagementInterface */
    private $linkManagement;

    /**
     * SaveProduct constructor.
     * @param LinkManagementInterface $linkManagement
     */
    public function __construct(LinkManagementInterface $linkManagement)
    {
        $this->linkManagement = $linkManagement;
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

            $product->setDeliveryTimeInherit(false)
                ->setDeliveryTimeFromSimple(false);
            $product->getResource()
                ->saveAttribute($product, AddDeliveryTimeAttributes::DELIVERY_TIME_INHERIT)
                ->saveAttribute($product, AddDeliveryTimeAttributes::DELIVERY_TIME_FROM_SIMPLE);

            if ($inherit && !$fromSimple) {
                $sku = $product->getSku();
                $type = $product->getDeliveryTimeType();
                $min = $product->getDeliveryTimeMin();
                $max = $product->getDeliveryTimeMax();
                $childProducts = $this->linkManagement->getChildren($sku);

                foreach ($childProducts as $childProduct) {
                    $childProduct->setDeliveryTimeType($type)
                        ->setDeliveryTimeMin($min)
                        ->setDeliveryTimeMax($max);

                    $childProduct->getResource()
                        ->saveAttribute($childProduct, AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE)
                        ->saveAttribute($childProduct, AddDeliveryTimeAttributes::DELIVERY_TIME_MIN)
                        ->saveAttribute($childProduct, AddDeliveryTimeAttributes::DELIVERY_TIME_MAX);
                }
            }
        }
    }
}
