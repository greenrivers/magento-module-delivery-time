<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Plugin\Model\ResourceModel\Fulltext\Collection;

use Greenrivers\DeliveryTime\Plugin\Model\Config;
use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection\SearchCriteriaResolverInterface as Subject;
use Magento\Framework\Api\Search\SearchCriteria;

class SearchCriteriaResolver
{
    /**
     * @param Subject $subject
     * @param SearchCriteria $result
     * @return SearchCriteria
     */
    public function afterResolve(Subject $subject, SearchCriteria $result): SearchCriteria
    {
        $sortOrders = $result->getSortOrders();
        unset($sortOrders[Config::DELIVERY_TIME_SORT_ORDER]);
        $result->setSortOrders($sortOrders);

        return $result;
    }
}
