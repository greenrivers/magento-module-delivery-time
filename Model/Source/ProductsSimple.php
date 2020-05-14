<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Model\Source;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\ConfigurableProduct\Api\LinkManagementInterface;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Unexpected\DeliveryTime\Helper\Render;

class ProductsSimple extends AbstractSource
{
    /** @var LocatorInterface */
    private $locator;

    /** @var LinkManagementInterface */
    private $linkManagement;

    /** @var Render */
    private $render;

    /**
     * ProductsSimple constructor.
     * @param LocatorInterface $locator
     * @param LinkManagementInterface $linkManagement
     * @param Render $render
     */
    public function __construct(LocatorInterface $locator, LinkManagementInterface $linkManagement, Render $render)
    {
        $this->locator = $locator;
        $this->linkManagement = $linkManagement;
        $this->render = $render;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        $optionArray = [];
        foreach ($this->toArray() as $key => $value) {
            $optionArray[] = [
                'value' => $key,
                'label' => $value
            ];
        }
        return $optionArray;
    }

    /**
     * @inheritDoc
     */
    public function getAllOptions(): array
    {
        return $this->toOptionArray();
    }

    /**
     * @return array
     */
    private function toArray(): array
    {
        $simpleProducts = [];
        $product = $this->locator->getProduct();
        $sku = $product->getSku();
        $childProducts = $this->linkManagement->getChildren($sku);
        foreach ($childProducts as $childProduct) {
            if ($childProduct->getDeliveryTimeType()) {
                $id = $childProduct->getId();
                $deliveryTime = $this->render->getFromProduct($childProduct);
                $simpleProducts[$id] = $childProduct->getSku() . ' => ' . $deliveryTime;
            }
        }
        return $simpleProducts;
    }
}
