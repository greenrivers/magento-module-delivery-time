<?php

namespace Greenrivers\DeliveryTime\Helper;

use Magento\Elasticsearch\Model\ResourceModel\Engine;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Search\Model\EngineResolver;

class Compatibility
{
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
        return $this->startsWith($this->productMetadata->getVersion(), '2.3') &&
            $this->scopeConfig->getValue(Engine::CONFIG_ENGINE_PATH) === EngineResolver::CATALOG_SEARCH_MYSQL_ENGINE;
    }

    /**
     * @param string $string
     * @param string $startString
     * @return bool
     */
    private function startsWith(string $string, string $startString): bool
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
}
