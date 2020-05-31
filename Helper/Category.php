<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Helper;

use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Psr\Log\LoggerInterface;
use Unexpected\DeliveryTime\Setup\Patch\Data\AddDeliveryTimeAttributes;

class Category
{
    const SORT_OPTION = 0;
    const FILTER_OPTION = 1;

    /** @var Config */
    private $config;

    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var Http */
    private $request;

    /** @var UrlInterface */
    private $url;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Filters constructor.
     * @param Config $config
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ProductRepositoryInterface $productRepository
     * @param Http $request
     * @param UrlInterface $url
     * @param LoggerInterface $logger
     */
    public function __construct(
        Config $config,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductRepositoryInterface $productRepository,
        Http $request,
        UrlInterface $url,
        LoggerInterface $logger
    ) {
        $this->config = $config;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productRepository = $productRepository;
        $this->request = $request;
        $this->url = $url;
        $this->logger = $logger;
    }

    /**
     * @param int $categoryId
     * @param int $type
     * @return bool
     */
    public function canShow(int $categoryId, int $type): bool
    {
        $condition = $type === self::SORT_OPTION ? $this->config->getSortConfig()
            : $this->config->getFilterConfig() && !$this->request->has(AddDeliveryTimeAttributes::DELIVERY_TIME_MAX);
        return $this->getProductCollectionByDeliveryTime(
            $categoryId,
            [
                    AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE_TO_VALUE,
                    AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE_RANGE_VALUE,
                    AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE_FROM_VALUE
            ]
        )->getTotalCount() && $condition;
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
     * @param Item $item
     * @return bool
     */
    public function isDeliveryTime(Item $item): bool
    {
        $attr = '';
        try {
            $attr = $item->getFilter()->getAttributeModel()->getAttributeCode();
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }
        return $attr === AddDeliveryTimeAttributes::DELIVERY_TIME_MAX;
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
    private function getProductCollectionByDeliveryTime(
        int $categoryId,
        array $deliveryTimeTypes
    ): ProductSearchResultsInterface {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('category_id', $categoryId, 'eq')
            ->addFilter(AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE, $deliveryTimeTypes, 'in')
            ->create();
        return $this->productRepository->getList($searchCriteria);
    }
}
