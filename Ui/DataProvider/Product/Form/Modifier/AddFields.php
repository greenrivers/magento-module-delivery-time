<?php

/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\RadioSet;
use Magento\Ui\Component\Form\Field;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Model\Source\RadioOptions;

class AddFields extends AbstractModifier
{
    /** @var Config */
    private $config;

    /** @var RadioOptions */
    private $radioOptions;

    /**
     * AddFields constructor.
     * @param Config $config
     * @param RadioOptions $radioOptions
     */
    public function __construct(Config $config, RadioOptions $radioOptions)
    {
        $this->config = $config;
        $this->radioOptions = $radioOptions;
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
        if (!$this->config->getEnableConfig()) {
            $meta['delivery-time']['arguments']['data']['config'] = [
                'componentType' => 'fieldset',
                'visible' => false
            ];
        }

        $meta = array_replace_recursive(
            $meta,
            [
                'delivery-time' => [
                    'children' => $this->getFields()
                ],
            ]
        );

        $meta['delivery-time']['children']['delivery_time_min_scale']['arguments']['data']['config']['visible'] = false;
        $meta['delivery-time']['children']['delivery_time_max_scale']['arguments']['data']['config']['visible'] = false;
        $meta['delivery-time']['children']['delivery_time_type']['arguments']['data']['config']['visible'] = false;

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
                            'sortOrder' => 10,
                            'switcherConfig' => [
                                'rules' => [
                                    '0' => [
                                        'value' => '0',
                                        'actions' => [
                                            '0' => [
                                                'target' => 'product_form.product_form.delivery-time.range',
                                                'callback' => 'show'
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
                            'sortOrder' => 20,
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
}
