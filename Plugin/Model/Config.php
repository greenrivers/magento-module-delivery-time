<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\Model;

use Magento\Catalog\Model\Config as Subject;
use Unexpected\DeliveryTime\Helper\Config as ConfigHelper;

class Config
{
    const DELIVERY_TIME_SORT_ORDER = 'delivery_time';

    /** @var ConfigHelper */
    private $config;

    /**
     * Config constructor.
     * @param ConfigHelper $config
     */
    public function __construct(ConfigHelper $config)
    {
        $this->config = $config;
    }

    /**
     * @param Subject $subject
     * @param array $result
     * @return array
     */
    public function afterGetAttributeUsedForSortByArray(Subject $subject, array $result): array
    {
        if ($this->config->getSortConfig()) {
            $result[self::DELIVERY_TIME_SORT_ORDER] = $this->config->getLabelConfig();
        }
        return $result;
    }
}
