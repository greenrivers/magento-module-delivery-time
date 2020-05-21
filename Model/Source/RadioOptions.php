<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class RadioOptions extends AbstractSource
{
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
        return [
            '0' => __('To'),
            '1' => __('From - to'),
            '2' => __('From'),
            '3' => __('None')
        ];
    }
}
