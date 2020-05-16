<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Unexpected\DeliveryTime\Helper\Config;

class ToggleSwitch extends Field
{
    const DELIVERY_TIME_GENERAL_ENABLE = 'delivery-time-general-enable';
    const DELIVERY_TIME_FRONTEND_ROUND_UP = 'delivery-time-frontend-round-up';
    const DELIVERY_TIME_FRONTEND_SORT = 'delivery-time-frontend-sort';
    const DELIVERY_TIME_FRONTEND_FILTER = 'delivery-time-frontend-filter';

    /** @var string */
    protected $_template = 'Unexpected_DeliveryTime::system/config/toggle_switch.phtml';

    /** @var Config */
    private $config;

    /** @var AbstractElement */
    private $element;

    /**
     * Checkbox constructor.
     * @param Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(Context $context, Config $config, array $data = [])
    {
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getComponent(): array
    {
        $element = $this->getElement();
        $id = str_replace('_', '-', $element->getHtmlId());
        $name = $element->getName();
        $component = ['id' => $id, 'name' => $name];

        switch ($id) {
            case self::DELIVERY_TIME_GENERAL_ENABLE:
                $component['value'] = $this->config->getEnableConfig();
                break;
            case self::DELIVERY_TIME_FRONTEND_ROUND_UP:
                $component['value'] = $this->config->getRoundUpConfig();
                break;
            case self::DELIVERY_TIME_FRONTEND_SORT:
                $component['value'] = $this->config->getSortConfig();
                break;
            case self::DELIVERY_TIME_FRONTEND_FILTER:
                $component['value'] = $this->config->getFilterConfig();
                break;
        }

        return $component;
    }

    /**
     * @inheritDoc
     */
    public function render(AbstractElement $element): string
    {
        $this->setElement($element);
        $html = "<td class='label'>" . $element->getLabel() . '</td><td>' . $this->toHtml() . '</td><td></td>';
        return $this->_decorateRowHtml($element, $html);
    }

    /**
     * @return AbstractElement
     */
    public function getElement(): AbstractElement
    {
        return $this->element;
    }

    /**
     * @param AbstractElement $element
     */
    public function setElement(AbstractElement $element): void
    {
        $this->element = $element;
    }
}
