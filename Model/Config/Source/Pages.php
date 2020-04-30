<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Pages implements OptionSourceInterface
{
    /**
     * @return array
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
     * @return string[]
     */
    private function toArray(): array
    {
        return [
            'product' => __('Product'),
            'days' => __('Mini cart'),
            'weeks' => __('Cart'),
            'months' => __('Checkout summary'),
            'years' => __('Order new mail'),
            'years0' => __('Invoice'),
            'years2' => __('Order view customer'),
            'years3' => __('Order view adminhtml'),
        ];
    }
}
