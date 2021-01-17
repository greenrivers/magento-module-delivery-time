<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Helper;

use Magento\Elasticsearch\Model\ResourceModel\Engine;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Search\Model\EngineResolver;
use Magento\Store\Model\ScopeInterface;

class Compatibility
{
    const MAGENTO_VERSION_23 = '2.3';

    /** @var ProductMetadataInterface */
    private $productMetadata;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /**
     * Compatibility constructor.
     * @param ProductMetadataInterface $productMetadata
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ProductMetadataInterface $productMetadata, ScopeConfigInterface $scopeConfig)
    {
        $this->productMetadata = $productMetadata;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function canSortBy(): bool
    {
        return $this->startsWith($this->productMetadata->getVersion(), self::MAGENTO_VERSION_23) &&
            $this->scopeConfig->getValue(Engine::CONFIG_ENGINE_PATH, ScopeInterface::SCOPE_STORE) ===
            EngineResolver::CATALOG_SEARCH_MYSQL_ENGINE;
    }

    /**
     * @param string $string
     * @param string $startString
     * @return bool
     */
    private function startsWith(string $string, string $startString): bool
    {
        $len = strlen($startString);
        return substr($string, 0, $len) === $startString;
    }
}
