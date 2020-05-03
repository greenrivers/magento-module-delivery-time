<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Helper;

class OrderView
{
    const DELIVERY_TIME_COLUMN = 'delivery-time';
    const POSITION = 4;

    /**
     * @param array $columns
     * @param array $column
     * @param int $position
     * @return array
     */
    public function addColumn(array $columns, array $column, int $position): array
    {
        return $this->insertArrayAtPosition($columns, $column, $position);
    }

    /**
     * @param array $array
     * @param array $insert
     * @param int $position
     * @return array
     */
    private function insertArrayAtPosition(array $array, array $insert, int $position): array
    {
        return array_slice($array, 0, $position, TRUE)
            + $insert + array_slice($array, $position, NULL, TRUE);
    }
}
