<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class DateUnit implements OptionSourceInterface
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
     * @return array
     */
    private function toArray(): array
    {
        return [
            'hours' => __('Hour'),
            'days' => __('Day'),
            'weeks' => __('Week'),
            'months' => __('Month'),
            'years' => __('Year')
        ];
    }
}
