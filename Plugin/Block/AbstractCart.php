<?php

/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\Block;

use Magento\Checkout\Block\Cart\AbstractCart as Subject;
use Magento\Framework\View\Element\Template;

class AbstractCart
{
    const CART_ITEM_DEFAULT_TEMPLATE = 'Unexpected_DeliveryTime::cart/item/default.phtml';

    /**
     * @param Subject $subject
     * @param Template $result
     * @return Template
     */
    public function afterGetItemRenderer(Subject $subject, Template $result): Template
    {
        $result->setTemplate(self::CART_ITEM_DEFAULT_TEMPLATE);
        return $result;
    }
}
