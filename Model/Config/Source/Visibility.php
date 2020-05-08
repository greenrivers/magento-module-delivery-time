<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Model\Config\Source;

use Magento\Framework\App\Area;
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
    const INVOICE_PRINT = 'sales_order_invoice_print';
    const ORDER_VIEW_CUSTOMER_PAGE = 'sales_order_view_' . Area::AREA_FRONTEND;
    const ORDER_VIEW_PRINT = 'sales_order_print';
    const ORDER_VIEW_ADMIN_PAGE = 'sales_order_view_' . Area::AREA_ADMINHTML;
    const SHIPMENT_NEW = 'adminhtml_order_shipment_new';
    const SHIPMENT_VIEW = 'adminhtml_order_shipment_view';
    const SHIPMENT_PRINT = 'sales_shipment_print';
    const SHIPMENT_NEW_MAIL = 'sales_email_order_shipment_items';
    const CREDITMEMO_NEW = 'sales_order_creditmemo_new';
    const CREDITMEMO_VIEW = 'sales_order_creditmemo_view';
    const CREDITMEMO_PRINT = 'sales_order_creditmemo_print';
    const INVOICE_MAIL = 'sales_email_order_invoice_items';
    const CREDITMEMO_MAIL = 'sales_email_order_creditmemo_items';
    const ORDER_CREATE_ADMIN_PAGE = 'sales_order_create_index';
    const INVOICE_VIEW_CUSTOMER_PAGE = 'sales_order_invoice';
    const INVOICE_PRINT_CUSTOMER_PAGE = 'sales_order_printInvoice';
    const SHIPMENT_VIEW_CUSTOMER_PAGE = 'sales_order_shipment';
    const SHIPMENT_PRINT_CUSTOMER_PAGE = 'sales_order_printShipment';
    const CREDITMEMO_VIEW_CUSTOMER_PAGE = 'sales_order_creditmemo';
    const CREDITMEMO_PRINT_CUSTOMER_PAGE = 'sales_order_printCreditmemo';

    /**
     * @inheritDoc
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
     * @return array
     */
    private function toArray(): array
    {
        return [
            self::PRODUCT_PAGE => __('Product'),
            self::MINI_CART_PAGE => __('Mini cart'),
            self::CART_PAGE => __('Cart'),
            self::CHECKOUT_PAGE => __('Checkout'),
            self::ORDER_NEW_MAIL => __('Order new mail'),
            self::INVOICE_NEW . ' ' . self::INVOICE_VIEW . ' ' . self::INVOICE_PRINT => __('Invoice'),
            self::ORDER_VIEW_CUSTOMER_PAGE . ' ' . self::ORDER_VIEW_PRINT => __('Order view customer'),
            self::ORDER_VIEW_ADMIN_PAGE => __('Order view adminhtml'),
            self::SHIPMENT_NEW . ' ' . self::SHIPMENT_VIEW . ' ' . self::SHIPMENT_PRINT => __('Shipment view adminhtml'),
            self::SHIPMENT_NEW_MAIL => __('Shipment new mail'),
            self::CREDITMEMO_NEW . ' ' . self::CREDITMEMO_VIEW . ' ' . self::CREDITMEMO_PRINT => __('Creditmemo'),
            self::INVOICE_MAIL => __('Invoice mail'),
            self::CREDITMEMO_MAIL => __('Creditmemo mail'),
            self::ORDER_CREATE_ADMIN_PAGE => __('Order create adminhtml'),
            self::INVOICE_VIEW_CUSTOMER_PAGE . ' ' . self::INVOICE_PRINT_CUSTOMER_PAGE => __('Invoice view customer'),
            self::SHIPMENT_VIEW_CUSTOMER_PAGE . ' ' . self::SHIPMENT_PRINT_CUSTOMER_PAGE => __('Shipment view customer'),
            self::CREDITMEMO_VIEW_CUSTOMER_PAGE . ' ' . self::CREDITMEMO_PRINT_CUSTOMER_PAGE => __('Creditmemo view customer')
        ];
    }
}
