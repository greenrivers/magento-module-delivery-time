<?php

/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class DateUnit implements OptionSourceInterface
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
            '' => __('Please select'),
            'hours' => __('Hour'),
            'days' => __('Day'),
            'weeks' => __('Week'),
            'months' => __('Month'),
            'years' => __('Year')
        ];
    }
}
