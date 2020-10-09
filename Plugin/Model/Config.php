<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_DeliveryTime
 */

namespace GreenRivers\DeliveryTime\Plugin\Model;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Config as Subject;
use Magento\Catalog\Model\Layer\Resolver;
use GreenRivers\DeliveryTime\Helper\Category as CategoryHelper;
use GreenRivers\DeliveryTime\Helper\Config as ConfigHelper;

class Config
{
    const DELIVERY_TIME_SORT_ORDER = 'delivery_time';

    /** @var ConfigHelper */
    private $config;

    /** @var CategoryHelper */
    private $category;

    /** @var Resolver */
    private $layerResolver;

    /**
     * Config constructor.
     * @param ConfigHelper $config
     * @param CategoryHelper $category
     * @param Resolver $layerResolver
     */
    public function __construct(ConfigHelper $config, CategoryHelper $category, Resolver $layerResolver)
    {
        $this->config = $config;
        $this->category = $category;
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
        if ($this->category->canShow($categoryId, CategoryHelper::SORT_OPTION)) {
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
