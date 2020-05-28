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
use Magento\Framework\App\Request\Http;
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

    /** @var Http */
    private $request;

    /** @var UrlInterface */
    private $url;

    /**
     * DeliveryTimeFilter constructor.
     * @param Config $config
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Http $request
     * @param UrlInterface $url
     */
    public function __construct(
        Config $config,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Http $request,
        UrlInterface $url
    ) {
        $this->config = $config;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->request = $request;
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
    public function getMaxValue(int $categoryId): int
    {
        $collection = $this->getProductCollection($categoryId);
        $products = $collection->getItems();
        $max = reset($products)->getDeliveryTimeMax();
        foreach ($products as $product) {
            if ($max < $product->getDeliveryTimeMax()) {
                $max = $product->getDeliveryTimeMax();
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
            AddDeliveryTimeAttributes::DELIVERY_TIME_MAX =>
                $this->request->has(AddDeliveryTimeAttributes::DELIVERY_TIME_MAX) ?
                    $this->request->get(AddDeliveryTimeAttributes::DELIVERY_TIME_MAX) : 1
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
