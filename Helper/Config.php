<?php

/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const XML_ENABLE_CONFIG_PATH = 'delivery_time/general/enable';
    const XML_DATE_UNIT_CONFIG_PATH = 'delivery_time/general/date_unit';
    const XML_MIN_SCALE_CONFIG_PATH = 'delivery_time/general/min_scale';
    const XML_MAX_SCALE_CONFIG_PATH = 'delivery_time/general/max_scale';
    const XML_SCALE_STEP_CONFIG_PATH = 'delivery_time/general/scale_step';

    /**
     * @return string
     */
    public function getEnableConfig(): string
    {
        return $this->scopeConfig->getValue(self::XML_ENABLE_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getDateUnitConfig(): string
    {
        return $this->scopeConfig->getValue(self::XML_DATE_UNIT_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getMinScaleConfig(): string
    {
        return $this->scopeConfig->getValue(self::XML_MIN_SCALE_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getMaxScaleConfig(): string
    {
        return $this->scopeConfig->getValue(self::XML_MAX_SCALE_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getScaleStepConfig(): string
    {
        return $this->scopeConfig->getValue(self::XML_SCALE_STEP_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
}
