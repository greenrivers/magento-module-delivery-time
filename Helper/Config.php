<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const XML_ENABLED_CONFIG_PATH = 'delivery_time/general/enabled';

    const XML_DATE_UNIT_CONFIG_PATH = 'delivery_time/backend/date_unit';
    const XML_MIN_SCALE_CONFIG_PATH = 'delivery_time/backend/min_scale';
    const XML_MAX_SCALE_CONFIG_PATH = 'delivery_time/backend/max_scale';
    const XML_SCALE_STEP_CONFIG_PATH = 'delivery_time/backend/scale_step';

    const XML_LABEL_CONFIG_PATH = 'delivery_time/frontend/label';
    const XML_SORT_CONFIG_PATH = 'delivery_time/frontend/sort';
    const XML_FILTER_CONFIG_PATH = 'delivery_time/frontend/filter';
    const XML_VISIBILITY_CONFIG_PATH = 'delivery_time/frontend/visibility';

    /**
     * @return bool
     */
    public function getEnabledConfig(): bool
    {
        return $this->scopeConfig->getValue(self::XML_ENABLED_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getDateUnitConfig(): string
    {
        return $this->scopeConfig->getValue(self::XML_DATE_UNIT_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return int
     */
    public function getMinScaleConfig(): int
    {
        return $this->scopeConfig->getValue(self::XML_MIN_SCALE_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return int
     */
    public function getMaxScaleConfig(): int
    {
        return $this->scopeConfig->getValue(self::XML_MAX_SCALE_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return int
     */
    public function getScaleStepConfig(): int
    {
        return $this->scopeConfig->getValue(self::XML_SCALE_STEP_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getLabelConfig(): string
    {
        return $this->scopeConfig->getValue(self::XML_LABEL_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function getSortConfig(): bool
    {
        return $this->scopeConfig->getValue(self::XML_SORT_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function getFilterConfig(): bool
    {
        return $this->scopeConfig->getValue(self::XML_FILTER_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     */
    public function getVisibilityConfig(): array
    {
        $visibility =
            $this->scopeConfig->getValue(self::XML_VISIBILITY_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
        return explode(
            ',',
            str_replace(' ', ',', $visibility ?? '')
        );
    }
}
