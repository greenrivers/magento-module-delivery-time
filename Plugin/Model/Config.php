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

    public function afterGetAttributeUsedForSortByArray(Subject $subject, array $result): array
    {
        $result['delivery_time'] = $this->config->getLabelConfig();
        return $result;
    }
}
