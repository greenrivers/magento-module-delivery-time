<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Model\Repository;

use Exception;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Greenrivers\DeliveryTime\Api\Data\DeliveryTimeInterface;
use Greenrivers\DeliveryTime\Api\DeliveryTimeRepositoryInterface;
use Greenrivers\DeliveryTime\Model\ResourceModel\DeliveryTime as DeliveryTimeResource;
use Greenrivers\DeliveryTime\Model\ResourceModel\DeliveryTime\Collection;
use Greenrivers\DeliveryTime\Model\ResourceModel\DeliveryTime\CollectionFactory as DeliveryTimeCollectionFactory;
use Greenrivers\DeliveryTime\Model\DeliveryTimeFactory;

class DeliveryTimeRepository implements DeliveryTimeRepositoryInterface
{
    /** @var DeliveryTimeFactory */
    private $deliveryTimeFactory;

    /** @var DeliveryTimeResource */
    private $deliveryTimeResource;

    /** @var DeliveryTimeCollectionFactory */
    private $deliveryTimeCollectionFactory;

    /** @var SearchResultsInterfaceFactory */
    private $searchResultsFactory;

    /** @var CollectionProcessorInterface */
    private $collectionProcessor;

    /** @var array */
    private $deliveryTimes;

    /**
     * DeliveryTimeRepository constructor.
     * @param DeliveryTimeFactory $deliveryTimeFactory
     * @param DeliveryTimeResource $deliveryTimeResource
     * @param DeliveryTimeCollectionFactory $deliveryTimeCollectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        DeliveryTimeFactory $deliveryTimeFactory,
        DeliveryTimeResource $deliveryTimeResource,
        DeliveryTimeCollectionFactory $deliveryTimeCollectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->deliveryTimeFactory = $deliveryTimeFactory;
        $this->deliveryTimeResource = $deliveryTimeResource;
        $this->deliveryTimeCollectionFactory = $deliveryTimeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(DeliveryTimeInterface $deliveryTime): DeliveryTimeInterface
    {
        try {
            $this->deliveryTimeResource->save($deliveryTime);
        } catch (Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        unset($this->deliveryTimes[$deliveryTime->getDeliveryTimeId()]);
        return $deliveryTime;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $deliveryTimeId): DeliveryTimeInterface
    {
        if (!isset($this->deliveryTimes[$deliveryTimeId])) {
            /** @var DeliveryTimeInterface $deliveryTime */
            $deliveryTime = $this->deliveryTimeFactory->create();
            $this->deliveryTimeResource->load($deliveryTime, $deliveryTimeId);
            if (!$deliveryTime->hasDeliveryTimeId() || !$deliveryTime->getDeliveryTimeId()) {
                throw new NoSuchEntityException(__('Delivery Time with id "%1" does not exist.', $deliveryTimeId));
            }
            $this->deliveryTimes[$deliveryTimeId] = $deliveryTime;
        }
        return $this->deliveryTimes[$deliveryTimeId];
    }

    /**
     * @inheritDoc
     */
    public function getByOrderItemId(int $orderItemId): DeliveryTimeInterface
    {
        /** @var DeliveryTimeInterface $deliveryTime */
        $deliveryTime = $this->deliveryTimeFactory->create();
        $this->deliveryTimeResource->load($deliveryTime, $orderItemId, DeliveryTimeInterface::ORDER_ITEM_ID);
        if (!$deliveryTime || !$deliveryTime->hasDeliveryTimeId() || !$deliveryTime->getDeliveryTimeId()) {
            throw new NoSuchEntityException(
                __('Delivery Time with specified order item id "%1" not found.', $orderItemId)
            );
        }
        $this->deliveryTimes[$deliveryTime->getDeliveryTimeId()] = $deliveryTime;
        return $deliveryTime;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultInterface
    {
        /** @var Collection $collection */
        $collection = $this->deliveryTimeCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var SearchResultInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria)
            ->setItems($collection->getItems())
            ->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(DeliveryTimeInterface $deliveryTime): bool
    {
        try {
            $deliveryTimeId = $deliveryTime->getDeliveryTimeId();
            $this->deliveryTimeResource->delete($deliveryTime);
        } catch (Exception $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        }
        unset($this->deliveryTimes[$deliveryTimeId]);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $deliveryTimeId): bool
    {
        return $this->delete($this->getById($deliveryTimeId));
    }
}
