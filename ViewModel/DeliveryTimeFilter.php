<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\ViewModel;

use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Helper\Filters;

class DeliveryTimeFilter implements ArgumentInterface
{
    /** @var Config */
    private $config;

    /** @var Filters */
    private $filters;

    /**
     * DeliveryTimeFilter constructor.
     * @param Config $config
     * @param Filters $filters
     */
    public function __construct(Config $config, Filters $filters)
    {
        $this->config = $config;
        $this->filters = $filters;
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
        return $this->config->getFilterConfig() && $this->filters->canShowOnFilters($categoryId);
    }

    /**
     * @param int $categoryId
     * @return int
     */
    public function getMaxValue(int $categoryId): int
    {
        return $this->filters->getMaxValue($categoryId);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->filters->getUrl();
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function isDeliveryTime(Item $item): bool
    {
        return $this->filters->isDeliveryTime($item);
    }
}
