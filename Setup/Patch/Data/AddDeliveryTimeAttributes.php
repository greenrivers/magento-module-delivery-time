<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Setup\Patch\Data;

use Exception;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;
use Unexpected\DeliveryTime\Model\Source\ProductsSimple;
use Unexpected\DeliveryTime\Model\Source\RadioOptions;

class AddDeliveryTimeAttributes implements DataPatchInterface
{
    const DELIVERY_TIME_TYPE = 'delivery_time_type';
    const DELIVERY_TIME_MIN = 'delivery_time_min';
    const DELIVERY_TIME_MAX = 'delivery_time_max';

    const DELIVERY_TIME_INHERIT = 'delivery_time_inherit';
    const DELIVERY_TIME_FROM_SIMPLE = 'delivery_time_from_simple';
    const DELIVERY_TIME_PRODUCT_SIMPLE = 'delivery_time_product_simple';

    const DELIVERY_TIME_TYPE_TO_VALUE = 0;
    const DELIVERY_TIME_TYPE_RANGE_VALUE = 1;
    const DELIVERY_TIME_TYPE_FROM_VALUE = 2;
    const DELIVERY_TIME_TYPE_NONE_VALUE = 3;

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
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
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
                self::DELIVERY_TIME_MIN => 'Delivery time min',
                self::DELIVERY_TIME_MAX => 'Delivery time max'
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
                    'comparable' => false,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'is_used_in_grid' => true,
                    'is_filterable_in_grid' => true,
                    'unique' => false
                ]);
            }

            $eavSetup->addAttribute(Product::ENTITY, self::DELIVERY_TIME_TYPE, [
                'type' => 'int',
                'backend' => ArrayBackend::class,
                'label' => 'Delivery time type',
                'input' => 'select',
                'group' => $attributeGroupName,
                'source' => RadioOptions::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => self::DELIVERY_TIME_TYPE_NONE_VALUE,
                'searchable' => false,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'is_used_in_grid' => true,
                'is_filterable_in_grid' => true,
                'unique' => false
            ]);

            $eavSetup->addAttribute(Product::ENTITY, self::DELIVERY_TIME_INHERIT, [
                'type' => 'int',
                'sort_order' => 10,
                'label' => 'Apply to simple products',
                'input' => 'boolean',
                'group' => $attributeGroupName,
                'source' => Boolean::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'is_used_in_grid' => false,
                'is_filterable_in_grid' => false,
                'unique' => false,
                'apply_to' => Configurable::TYPE_CODE
            ]);

            $eavSetup->addAttribute(Product::ENTITY, self::DELIVERY_TIME_FROM_SIMPLE, [
                'type' => 'int',
                'sort_order' => 20,
                'label' => 'Apply from simple product',
                'input' => 'boolean',
                'group' => $attributeGroupName,
                'source' => Boolean::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'is_used_in_grid' => false,
                'is_filterable_in_grid' => false,
                'unique' => false,
                'apply_to' => Configurable::TYPE_CODE
            ]);

            $eavSetup->addAttribute(Product::ENTITY, self::DELIVERY_TIME_PRODUCT_SIMPLE, [
                'type' => 'int',
                'sort_order' => 30,
                'backend' => ArrayBackend::class,
                'label' => 'Select product',
                'input' => 'select',
                'group' => $attributeGroupName,
                'source' => ProductsSimple::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'is_used_in_grid' => false,
                'is_filterable_in_grid' => false,
                'unique' => false,
                'apply_to' => Configurable::TYPE_CODE
            ]);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
