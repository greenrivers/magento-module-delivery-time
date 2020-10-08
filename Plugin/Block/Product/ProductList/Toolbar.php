<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\Block\Product\ProductList;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Block\Product\ProductList\Toolbar as Subject;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Unexpected\DeliveryTime\Setup\Patch\Data\AddDeliveryTimeAttributes;
use Zend_Db_Expr;
use Unexpected\DeliveryTime\Plugin\Model\Config;

class Toolbar
{
    /** @var ProductAttributeRepositoryInterface */
    private $productAttributeRepository;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Toolbar constructor.
     * @param ProductAttributeRepositoryInterface $productAttributeRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        ProductAttributeRepositoryInterface $productAttributeRepository,
        LoggerInterface $logger
    ) {
        $this->productAttributeRepository = $productAttributeRepository;
        $this->logger = $logger;
    }

    /**
     * @param Subject $subject
     * @param Subject $result
     * @return Subject
     */
    public function afterSetCollection(Subject $subject, Subject $result): Subject
    {
        $currentOrder = $subject->getCurrentOrder();
        $currentDirection = $subject->getCurrentDirection();

        if ($currentOrder && $currentOrder === Config::DELIVERY_TIME_SORT_ORDER) {
            $deliveryTimeType = $this->getCondition(AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE);
            $deliveryTimeMin = $this->getCondition(AddDeliveryTimeAttributes::DELIVERY_TIME_MIN);
            $deliveryTimeMax = $this->getCondition(AddDeliveryTimeAttributes::DELIVERY_TIME_MAX);

            $subject->getCollection()->getSelect()
                ->join(
                    'catalog_product_entity_int',
                    "e.entity_id = catalog_product_entity_int.entity_id",
                    [Config::DELIVERY_TIME_SORT_ORDER => 'value']
                )
                ->group('e.entity_id')
                ->columns([
                    AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE => new Zend_Db_Expr($deliveryTimeType),
                    AddDeliveryTimeAttributes::DELIVERY_TIME_MIN => new Zend_Db_Expr($deliveryTimeMin),
                    AddDeliveryTimeAttributes::DELIVERY_TIME_MAX => new Zend_Db_Expr($deliveryTimeMax)
                ])
                ->order(AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE . ' ' . $currentDirection)
                ->order(
                    new Zend_Db_Expr(
                        "CASE WHEN ${deliveryTimeType} IN (1, 2) THEN ${deliveryTimeMax}
                                        WHEN ${deliveryTimeType} = 3 THEN ${deliveryTimeMin}
                                        END ${currentDirection}"
                    )
                )
                ->order(
                    new Zend_Db_Expr(
                        "CASE WHEN ${deliveryTimeType} = 2 THEN ${deliveryTimeMax}
                                        END ${currentDirection}"
                    )
                );
        }

        return $result;
    }

    /**
     * @param Subject $subject
     * @param int $result
     * @return int
     */
    public function afterGetTotalNum(Subject $subject, int $result): int
    {
        return count($subject->getCollection()->getAllIds());
    }

    /**
     * @param string $attributeCode
     * @return string
     */
    private function getCondition(string $attributeCode): string
    {
        $cond = '';
        try {
            $id = $this->productAttributeRepository->get($attributeCode)->getAttributeId();
            $cond = "MAX(
                CASE WHEN catalog_product_entity_int.attribute_id = ${id}
                THEN catalog_product_entity_int.value END
            )";
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
        }
        return $cond;
    }
}
