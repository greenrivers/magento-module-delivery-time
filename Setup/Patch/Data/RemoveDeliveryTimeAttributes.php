<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Setup\Patch\Data;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Psr\Log\LoggerInterface;

class RemoveDeliveryTimeAttributes implements PatchRevertableInterface
{
    /** @var ModuleDataSetupInterface */
    private $moduleDataSetup;

    /** @var EavSetupFactory */
    private $eavSetupFactory;

    /** @var ProductAttributeRepositoryInterface */
    private $productAttributeRepository;

    /** @var LoggerInterface */
    private $logger;

    /**
     * RemoveDeliveryTimeAttributes constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param ProductAttributeRepositoryInterface $productAttributeRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        ProductAttributeRepositoryInterface $productAttributeRepository,
        LoggerInterface $logger
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function revert(): void
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $this->removeAttributes($eavSetup);
    }

    /**
     * @param EavSetup $eavSetup
     */
    private function removeAttributes(EavSetup $eavSetup): void
    {
        $attrCodes = [
            AddDeliveryTimeAttributes::DELIVERY_TIME_MIN,
            AddDeliveryTimeAttributes::DELIVERY_TIME_MAX,
            AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE,
            AddDeliveryTimeAttributes::DELIVERY_TIME_INHERIT,
            AddDeliveryTimeAttributes::DELIVERY_TIME_FROM_SIMPLE,
            AddDeliveryTimeAttributes::DELIVERY_TIME_PRODUCT_SIMPLE
        ];

        foreach ($attrCodes as $attrCode) {
            try {
                $attribute = $this->productAttributeRepository->get($attrCode);
                $eavSetup->removeAttribute($attribute->getAttributeId(), $attrCode);
            } catch (NoSuchEntityException $e) {
                $this->logger->error($e->getMessage());
            }
        }
    }
}
