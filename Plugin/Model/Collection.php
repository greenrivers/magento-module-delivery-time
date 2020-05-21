<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\Model;

use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection as Subject;

class Collection
{
    /**
     * @param Subject $subject
     * @param int $result
     * @return int
     */
    public function afterGetLastPageNumber(Subject $subject, int $result): int
    {
        $collectionSize = count($subject->getAllIds());
        if (0 === $collectionSize) {
            return 1;
        } elseif ($subject->getPageSize()) {
            return (int)ceil($collectionSize / $subject->getPageSize());
        } else {
            return 1;
        }
    }
}
