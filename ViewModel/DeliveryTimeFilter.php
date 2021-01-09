<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\ViewModel;

use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Greenrivers\DeliveryTime\Helper\Config;
use Greenrivers\DeliveryTime\Helper\Category;

class DeliveryTimeFilter implements ArgumentInterface
{
    /** @var Config */
    private $config;

    /** @var Category */
    private $category;

    /**
     * DeliveryTimeFilter constructor.
     * @param Config $config
     * @param Category $category
     */
    public function __construct(Config $config, Category $category)
    {
        $this->config = $config;
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->config->getLabelConfig();
    }

    /**
     * @return string
     */
    public function getDateUnit(): string
    {
        return $this->config->getDateUnitConfig();
    }

    /**
     * @param int $categoryId
     * @return bool
     */
    public function canShowOnFilters(int $categoryId): bool
    {
        return $this->category->canShow($categoryId, Category::FILTER_OPTION);
    }

    /**
     * @param int $categoryId
     * @return int
     */
    public function getMaxValue(int $categoryId): int
    {
        return $this->category->getMaxValue($categoryId);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->category->getUrl();
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function isDeliveryTime(Item $item): bool
    {
        return $this->category->isDeliveryTime($item);
    }
}
