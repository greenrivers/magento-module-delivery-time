<?php

namespace Unexpected\DeliveryTime\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class RadioOptions extends AbstractSource
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
     * @return array
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
            '0' => __('Up to'),
            '1' => __('From'),
            '2' => __('From - to')
        ];
    }
}
