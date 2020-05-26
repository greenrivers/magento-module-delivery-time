<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Preference\Model\Layer\Filter;

use Magento\CatalogSearch\Model\Layer\Filter\Attribute as BaseAttribute;
use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection;
use Magento\Framework\App\RequestInterface;
use Unexpected\DeliveryTime\Setup\Patch\Data\AddDeliveryTimeAttributes;

class Attribute extends BaseAttribute
{
    /**
     * @inheritDoc
     */
    public function apply(RequestInterface $request)
    {
        $attributeValue = $request->getParam($this->_requestVar);
        if (empty($attributeValue) && !is_numeric($attributeValue)) {
            return $this;
        }
        $attribute = $this->getAttributeModel();
        /** @var Collection $productCollection */
        $productCollection = $this->getLayer()
            ->getProductCollection();

        if ($this->_requestVar === AddDeliveryTimeAttributes::DELIVERY_TIME_MIN) {
            $productCollection
                ->addAttributeToFilter([
                    [
                        'attribute' => AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE,
                        'eq' => AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE_FROM_VALUE
                    ],
                    ['attribute' => $attribute->getAttributeCode(), 'gteq' => $attributeValue]
                ]);
        } else if ($this->_requestVar === AddDeliveryTimeAttributes::DELIVERY_TIME_MAX) {
            $productCollection
                ->addAttributeToFilter([
                    [
                        'attribute' => AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE,
                        'eq' => AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE_TO_VALUE
                    ],
                    ['attribute' => $attribute->getAttributeCode(), 'lteq' => $attributeValue]
                ]);
        } else {
            $productCollection->addFieldToFilter($attribute->getAttributeCode(), $attributeValue);
        }

        $label = $this->getOptionText($attributeValue);
        if (is_array($label)) {
            $label = implode(',', $label);
        }
        $this->getLayer()
            ->getState()
            ->addFilter($this->_createItem($label, $attributeValue));

        $this->setItems([]); // set items to disable show filtering
        return $this;
    }
}
