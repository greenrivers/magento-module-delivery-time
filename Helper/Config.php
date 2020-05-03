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

    const XML_DATE_UNIT_CONFIG_PATH = 'delivery_time/backend/date_unit';
    const XML_MIN_SCALE_CONFIG_PATH = 'delivery_time/backend/min_scale';
    const XML_MAX_SCALE_CONFIG_PATH = 'delivery_time/backend/max_scale';
    const XML_SCALE_STEP_CONFIG_PATH = 'delivery_time/backend/scale_step';

    const XML_LABEL_CONFIG_PATH = 'delivery_time/frontend/label';
    const XML_VISIBILITY_CONFIG_PATH = 'delivery_time/frontend/visibility';

    /**
     * @return int
     */
    public function getEnableConfig(): int
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
     * @return array
     */
    public function getVisibilityConfig(): array
    {
        return explode(
            ',',
            $this->scopeConfig->getValue(self::XML_VISIBILITY_CONFIG_PATH, ScopeInterface::SCOPE_STORE)
        );
    }
}
