<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Block\Adminhtml\System\Config;

use Greenrivers\DeliveryTime\Helper\Compatibility;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Greenrivers\DeliveryTime\Helper\Config;

class ToggleSwitch extends Field
{
    const DELIVERY_TIME_GENERAL_ENABLED = 'delivery-time-general-enabled';
    const DELIVERY_TIME_FRONTEND_SORT = 'delivery-time-frontend-sort';
    const DELIVERY_TIME_FRONTEND_FILTER = 'delivery-time-frontend-filter';

    /** @var string */
    protected $_template = 'Greenrivers_DeliveryTime::system/config/toggle_switch.phtml';

    /** @var Config */
    private $config;

    /** @var Compatibility */
    private $compatibility;

    /** @var AbstractElement */
    private $element;

    /**
     * Checkbox constructor.
     * @param Context $context
     * @param Config $config
     * @param Compatibility $compatibility
     * @param array $data
     */
    public function __construct(Context $context, Config $config, Compatibility $compatibility, array $data = [])
    {
        $this->config = $config;
        $this->compatibility = $compatibility;
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
            case self::DELIVERY_TIME_GENERAL_ENABLED:
                $component['value'] = $this->config->getEnabledConfig();
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
        $id = str_replace('_', '-', $element->getHtmlId());
        if ($id === self::DELIVERY_TIME_FRONTEND_SORT && !$this->compatibility->canSortBy()) {
            return '';
        }

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
