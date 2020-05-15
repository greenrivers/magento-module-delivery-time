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

class EnableSwitch extends Field
{
    /** @var string */
    protected $_template = 'Unexpected_DeliveryTime::system/config/enable_switch.phtml';

    /** @var Config */
    private $config;

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
     * @return bool
     */
    public function getValue(): bool
    {
        return $this->config->getEnableConfig();
    }

    /**
     * @inheritDoc
     */
    public function render(AbstractElement $element): string
    {
        $html = "<td class='label'>" . $element->getLabel() . '</td><td>' . $this->toHtml() . '</td><td></td>';
        return $this->_decorateRowHtml($element, $html);
    }
}
