<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Api;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Greenrivers\DeliveryTime\Api\Data\DeliveryTimeInterface;

interface DeliveryTimeRepositoryInterface
{
    /**
     * @param DeliveryTimeInterface $deliveryTime
     * @return DeliveryTimeInterface
     * @throws CouldNotSaveException
     */
    public function save(DeliveryTimeInterface $deliveryTime): DeliveryTimeInterface;

    /**
     * @param int $deliveryTimeId
     * @return DeliveryTimeInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $deliveryTimeId): DeliveryTimeInterface;

    /**
     * @param int $orderItemId
     * @return DeliveryTimeInterface
     * @throws NoSuchEntityException
     */
    public function getByOrderItemId(int $orderItemId): DeliveryTimeInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultInterface;

    /**
     * @param DeliveryTimeInterface $deliveryTime
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(DeliveryTimeInterface $deliveryTime): bool;

    /**
     * @param int $deliveryTimeId
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $deliveryTimeId): bool;
}
