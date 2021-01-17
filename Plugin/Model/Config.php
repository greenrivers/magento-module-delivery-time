<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Plugin\Model;

use Greenrivers\DeliveryTime\Helper\Compatibility;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Config as Subject;
use Magento\Catalog\Model\Layer\Resolver;
use Greenrivers\DeliveryTime\Helper\Category as CategoryHelper;
use Greenrivers\DeliveryTime\Helper\Config as ConfigHelper;

class Config
{
    const DELIVERY_TIME_SORT_ORDER = 'delivery_time';

    /** @var ConfigHelper */
    private $config;

    /** @var CategoryHelper */
    private $category;

    /** @var Compatibility */
    private $compatibility;

    /** @var Resolver */
    private $layerResolver;

    /**
     * Config constructor.
     * @param ConfigHelper $config
     * @param CategoryHelper $category
     * @param Compatibility $compatibility
     * @param Resolver $layerResolver
     */
    public function __construct(
        ConfigHelper $config,
        CategoryHelper $category,
        Compatibility $compatibility,
        Resolver $layerResolver
    ) {
        $this->config = $config;
        $this->category = $category;
        $this->compatibility = $compatibility;
        $this->layerResolver = $layerResolver;
    }

    /**
     * @param Subject $subject
     * @param array $result
     * @return array
     */
    public function afterGetAttributeUsedForSortByArray(Subject $subject, array $result): array
    {
        $categoryId = $this->getCurrentCategory()->getId();
        if ($this->compatibility->canSortBy() && $this->category->canShow($categoryId, CategoryHelper::SORT_OPTION)) {
            $result[self::DELIVERY_TIME_SORT_ORDER] = $this->config->getLabelConfig();
        }
        return $result;
    }

    /**
     * @return Category
     */
    private function getCurrentCategory(): Category
    {
        return $this->layerResolver->get()->getCurrentCategory();
    }
}
