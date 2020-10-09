<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_DeliveryTime
 */

namespace GreenRivers\DeliveryTime\Model\Config\Source;

use Magento\Framework\App\Area;
use Magento\Framework\Data\OptionSourceInterface;

class Visibility implements OptionSourceInterface
{
    const PRODUCT_PAGE = 'catalog_product_view';
    const ORDER_MAIL = 'sales_email_order_items';
    const ORDER_VIEW_CUSTOMER_PAGE = 'sales_order_view_' . Area::AREA_FRONTEND;
    const ORDER_VIEW_PRINT = 'sales_order_print';
    const ORDER_VIEW_ADMIN_PAGE = 'sales_order_view_' . Area::AREA_ADMINHTML;
    const ORDER_CREATE_ADMIN_PAGE = 'sales_order_create_index';
    const ORDER_CREATE_ADMIN_LOAD_BLOCK_PAGE = 'sales_order_create_loadBlock';

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
            self::ORDER_MAIL => __('Order mail'),
            self::ORDER_VIEW_ADMIN_PAGE => __('Order view admin'),
            self::ORDER_VIEW_CUSTOMER_PAGE . ' ' . self::ORDER_VIEW_PRINT => __('Order view customer'),
            self::ORDER_CREATE_ADMIN_PAGE . ' ' . self::ORDER_CREATE_ADMIN_LOAD_BLOCK_PAGE => __('Order create admin')
        ];
    }
}
