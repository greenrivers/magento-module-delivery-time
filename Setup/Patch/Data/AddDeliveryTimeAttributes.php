<?php

/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Setup\Patch\Data;

use Exception;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;
use Unexpected\DeliveryTime\Model\Source\RadioOptions;

class AddDeliveryTimeAttributes implements DataPatchInterface
{
    const DELIVERY_TIME_TYPE = 'delivery_time_type';
    const DELIVERY_TIME_MIN = 'delivery_time_min';
    const DELIVERY_TIME_MAX = 'delivery_time_max';

    /** @var ModuleDataSetupInterface */
    private $moduleDataSetup;

    /** @var EavSetupFactory */
    private $eavSetupFactory;

    /** @var LoggerInterface */
    private $logger;

    /**
     * AddDeliveryTimeAttributes constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        LoggerInterface $logger
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $attributeGroupName = 'Delivery Time';

        $this->createAttributeGroup($eavSetup, $attributeGroupName);
        $this->createAttributes($eavSetup, $attributeGroupName);
    }

    /**
     * @param EavSetup $eavSetup
     * @param string $attributeGroupName
     */
    private function createAttributeGroup(EavSetup $eavSetup, string $attributeGroupName): void
    {
        try {
            $entityTypeId = $eavSetup->getEntityTypeId(Product::ENTITY);
            $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);
            foreach ($attributeSetIds as $attributeSetId) {
                $eavSetup->addAttributeGroup(
                    Product::ENTITY,
                    $attributeSetId,
                    $attributeGroupName,
                    12
                );
            }
        } catch (LocalizedException $e) {
            $this->logger->error($e->getLogMessage());
        }
    }

    /**
     * @param EavSetup $eavSetup
     * @param string $attributeGroupName
     */
    private function createAttributes(EavSetup $eavSetup, string $attributeGroupName): void
    {
        try {
            $attrs = [
                self::DELIVERY_TIME_MIN => 'Min',
                self::DELIVERY_TIME_MAX => 'Max'
            ];

            foreach ($attrs as $attr => $label) {
                $eavSetup->addAttribute(Product::ENTITY, $attr, [
                    'type' => 'int',
                    'label' => $label,
                    'input' => 'text',
                    'group' => $attributeGroupName,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'searchable' => true,
                    'filterable' => true,
                    'comparable' => true,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'unique' => false
                ]);
            }

            $eavSetup->addAttribute(Product::ENTITY, self::DELIVERY_TIME_TYPE, [
                'type' => 'int',
                'backend' => ArrayBackend::class,
                'label' => 'Type',
                'input' => 'select',
                'group' => $attributeGroupName,
                'source' => RadioOptions::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false
            ]);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
