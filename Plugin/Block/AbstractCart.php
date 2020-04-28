<?php

namespace Unexpected\DeliveryTime\Block\Cart;

use Magento\Checkout\Block\Cart\AbstractCart as Subject;
use Magento\Framework\View\Element\Template;

class AbstractCart
{
    /**
     * @param Subject $subject
     * @param Template $result
     * @return Template
     */
    public function afterGetItemRenderer(Subject $subject, Template $result): Template
    {
        $result->setTemplate('Unexpected_DeliveryTime::cart/item/default.phtml');
        return $result;
    }
}
