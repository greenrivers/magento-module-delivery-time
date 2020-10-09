<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_DeliveryTime
 */

namespace GreenRivers\DeliveryTime\Plugin\Model\ResourceModel\Fulltext;

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
