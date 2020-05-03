<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Visibility implements OptionSourceInterface
{
    const PRODUCT_PAGE = 'catalog_product_view';
    const MINI_CART_PAGE = 'customer_section_load';
    const CART_PAGE = 'checkout_cart_index';
    const CHECKOUT_PAGE = 'checkout_index_index';
    const ORDER_NEW_MAIL = 'sales_email_order_items';
    const INVOICE_NEW = 'sales_order_invoice_new';
    const INVOICE_VIEW = 'sales_order_invoice_view';
    const ORDER_VIEW_CUSTOMER_PAGE = 'sales_order_view';
    const ORDER_VIEW_ADMIN_PAGE = 'sales_order_view';

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $optionArray = [];
        foreach ($this->toArray() as $key => $value) {
            $optionArray[] = [
                'value' => $key,
                'label' => $value
            ];
        }
        return $optionArray;
    }

    /**
     * @return string[]
     */
    private function toArray(): array
    {
        return [
            self::PRODUCT_PAGE => __('Product'),
            self::MINI_CART_PAGE => __('Mini cart'),
            self::CART_PAGE => __('Cart'),
            self::CHECKOUT_PAGE => __('Checkout'),
            self::ORDER_NEW_MAIL => __('Order new mail'),
            self::INVOICE_NEW . ',' . self::INVOICE_VIEW => __('Invoice'),
            self::ORDER_VIEW_CUSTOMER_PAGE => __('Order view customer'),
            self::ORDER_VIEW_ADMIN_PAGE => __('Order view adminhtml'),
        ];
    }
}
