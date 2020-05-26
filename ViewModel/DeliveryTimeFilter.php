<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\ViewModel;

use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Setup\Patch\Data\AddDeliveryTimeAttributes;

class DeliveryTimeFilter implements ArgumentInterface
{
    /** @var Config */
    private $config;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @var UrlInterface */
    private $url;

    /**
     * DeliveryTimeFilter constructor.
     * @param Config $config
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param UrlInterface $url
     */
    public function __construct(
        Config $config,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        UrlInterface $url
    ) {
        $this->config = $config;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->config->getLabelConfig();
    }

    /**
     * @param int $categoryId
     * @return bool
     */
    public function canShowOnFilters(int $categoryId): bool
    {
        return $this->config->getFilterConfig() &&
            $this->getProductCollectionByDeliveryTime(
                $categoryId,
                [
                    AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE_TO_VALUE,
                    AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE_RANGE_VALUE,
                    AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE_FROM_VALUE
                ]
            )->getTotalCount();
    }

    /**
     * @param int $categoryId
     * @return int
     */
    public function getMinValue(int $categoryId): int
    {
        $collection = $this->getProductCollection($categoryId);
        $deliveryTimeCollection = $this->getProductCollectionByDeliveryTime(
            $categoryId, [AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE_TO_VALUE]
        );
        $products = $collection->getItems();
        $min = reset($products)->getDeliveryTimeMin();
        if ($deliveryTimeCollection->getTotalCount()) {
            $min = $this->config->getMinScaleConfig();
        } else {
            foreach ($products as $product) {
                if ($min > $product->getDeliveryTimeMin()) {
                    $min = $product->getDeliveryTimeMin();
                }
            }
        }
        return $min;
    }

    /**
     * @param int $categoryId
     * @return int
     */
    public function getMaxValue(int $categoryId): int
    {
        $collection = $this->getProductCollection($categoryId);
        $deliveryTimeCollection = $this->getProductCollectionByDeliveryTime(
            $categoryId, [AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE_FROM_VALUE]
        );
        $products = $collection->getItems();
        $max = reset($products)->getDeliveryTimeMax();
        if ($deliveryTimeCollection->getTotalCount()) {
            $max = $this->config->getMaxScaleConfig();
        } else {
            foreach ($products as $product) {
                if ($max < $product->getDeliveryTimeMax()) {
                    $max = $product->getDeliveryTimeMax();
                }
            }
        }
        return $max;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        $query = [
            AddDeliveryTimeAttributes::DELIVERY_TIME_MIN => $this->config->getMinScaleConfig(),
            AddDeliveryTimeAttributes::DELIVERY_TIME_MAX => $this->config->getMaxScaleConfig()
        ];
        return $this->url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);
    }

    /**
     * @param int $categoryId
     * @return ProductSearchResultsInterface
     */
    private function getProductCollection(int $categoryId): ProductSearchResultsInterface
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('category_id', $categoryId, 'eq')
            ->create();
        return $this->productRepository->getList($searchCriteria);
    }

    /**
     * @param int $categoryId
     * @param array $deliveryTimeTypes
     * @return ProductSearchResultsInterface
     */
    private function getProductCollectionByDeliveryTime(int $categoryId, array $deliveryTimeTypes): ProductSearchResultsInterface
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('category_id', $categoryId, 'eq')
            ->addFilter(AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE, $deliveryTimeTypes, 'in')
            ->create();
        return $this->productRepository->getList($searchCriteria);
    }
}
