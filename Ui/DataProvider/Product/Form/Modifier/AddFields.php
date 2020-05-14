<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\ConfigurableProduct\Api\LinkManagementInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\RadioSet;
use Magento\Ui\Component\Form\Field;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Model\Source\RadioOptions;
use Unexpected\DeliveryTime\Setup\Patch\Data\AddDeliveryTimeAttributes;

class AddFields extends AbstractModifier
{
    /** @var Config */
    private $config;

    /** @var RadioOptions */
    private $radioOptions;

    /** @var LocatorInterface */
    private $locator;

    /** @var LinkManagementInterface */
    private $linkManagement;

    /**
     * AddFields constructor.
     * @param Config $config
     * @param RadioOptions $radioOptions
     * @param LocatorInterface $locator
     * @param LinkManagementInterface $linkManagement
     */
    public function __construct(
        Config $config,
        RadioOptions $radioOptions,
        LocatorInterface $locator,
        LinkManagementInterface $linkManagement
    ) {
        $this->config = $config;
        $this->radioOptions = $radioOptions;
        $this->locator = $locator;
        $this->linkManagement = $linkManagement;
    }

    /**
     * @inheritDoc
     */
    public function modifyData(array $data): array
    {
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta): array
    {
        $product = $this->locator->getProduct();

        if (!$this->config->getEnableConfig()) {
            $meta['delivery-time']['arguments']['data']['config']['visible'] = false;
        }

        $meta = array_replace_recursive(
            $meta,
            [
                'delivery-time' => [
                    'children' => $this->getFields()
                ],
            ]
        );

        $meta = array_replace_recursive(
            $meta,
            [
                'delivery-time' => [
                    'children' => [
                        AddDeliveryTimeAttributes::DELIVERY_TIME_MIN => $this->setConfig('visible', false),
                        AddDeliveryTimeAttributes::DELIVERY_TIME_MAX => $this->setConfig('visible', false),
                        AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE => $this->setConfig('visible', false)
                    ]
                ],
            ]
        );

        if ($product->getTypeId() === Configurable::TYPE_CODE) {
            $sku = $product->getSku();
            $childProducts = $this->linkManagement->getChildren($sku);
            $deliveryTimeFromSimple = $product->getDeliveryTimeFromSimple();
            $filterProducts = array_filter($childProducts, function ($childProduct){
                return !$childProduct->getDeliveryTimeType();
            });

            $meta = array_replace_recursive(
                $meta,
                [
                    'delivery-time' => [
                        'children' => [
                            AddDeliveryTimeAttributes::DELIVERY_TIME_INHERIT => $this->setConfig(
                                'disabled',
                                count($filterProducts) === 0
                            ),
                            AddDeliveryTimeAttributes::DELIVERY_TIME_FROM_SIMPLE => $this->setConfig(
                                'disabled',
                                count($filterProducts) === count($childProducts)
                            ),
                            AddDeliveryTimeAttributes::DELIVERY_TIME_PRODUCT_SIMPLE => $this->setConfig(
                                'visible',
                                $deliveryTimeFromSimple | 0
                            )
                        ]
                    ],
                ]
            );
        }

        return $meta;
    }

    /**
     * @return array
     */
    protected function getFields(): array
    {
        return [
            'type' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'dataType' => Text::NAME,
                            'formElement' => RadioSet::NAME,
                            'options' => $this->radioOptions->getAllOptions(),
                            'componentType' => Field::NAME,
                            'component' => 'Unexpected_DeliveryTime/js/form/element/radiobox-set',
                            'dataScope' => 'type',
                            'label' => __('Type'),
                            'fit' => true,
                            'additionalClasses' => 'admin__field-small',
                            'sortOrder' => 40,
                            'switcherConfig' => [
                                'rules' => [
                                    '0' => [
                                        'value' => '0',
                                        'actions' => [
                                            '0' => [
                                                'target' => 'product_form.product_form.delivery-time.range',
                                                'callback' => 'hide'
                                            ]
                                        ]
                                    ],
                                    '1' => [
                                        'value' => '1',
                                        'actions' => [
                                            '0' => [
                                                'target' => 'product_form.product_form.delivery-time.range',
                                                'callback' => 'show'
                                            ]
                                        ]
                                    ],
                                    '2' => [
                                        'value' => '2',
                                        'actions' => [
                                            '0' => [
                                                'target' => 'product_form.product_form.delivery-time.range',
                                                'callback' => 'show'
                                            ]
                                        ]
                                    ],
                                    '3' => [
                                        'value' => '3',
                                        'actions' => [
                                            '0' => [
                                                'target' => 'product_form.product_form.delivery-time.range',
                                                'callback' => 'show'
                                            ]
                                        ]
                                    ]
                                ],
                                'enabled' => true
                            ]
                        ],
                    ],
                ],
            ],
            'range' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Range'),
                            'componentType' => Field::NAME,
                            'component' => 'Unexpected_DeliveryTime/js/form/element/range',
                            'formElement' => Input::NAME,
                            'dataScope' => 'range',
                            'dataType' => Text::NAME,
                            'sortOrder' => 50,
                            'visible' => false,
                            'slider' => [
                                'dateUnit' => $this->config->getDateUnitConfig(),
                                'minScale' => $this->config->getMinScaleConfig(),
                                'maxScale' => $this->config->getMaxScaleConfig(),
                                'scaleStep' => $this->config->getScaleStepConfig(),
                            ]
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * @param string $param
     * @param bool $visible
     * @return array
     */
    private function setConfig(string $param, bool $visible): array
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        $param => $visible
                    ]
                ]
            ]
        ];
    }
}
