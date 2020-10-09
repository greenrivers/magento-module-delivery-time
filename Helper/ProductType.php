<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_DeliveryTime
 */

namespace GreenRivers\DeliveryTime\Helper;

use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Model\Order\Item;

class ProductType
{
    /**
     * @param QuoteItem $item
     * @return Product
     */
    public function getProductFromQuoteItem(QuoteItem $item): Product
    {
        return $item->getProductType() === Configurable::TYPE_CODE ?
            $item->getOptionByCode('simple_product')->getProduct() : $item->getProduct();
    }

    /**
     * @param Item $item
     * @return Product
     */
    public function getProductFromItem(Item $item): Product
    {
        return $item->getProductType() === Configurable::TYPE_CODE ?
            $item->getChildrenItems()[0]->getProduct() : $item->getProduct();
    }
}
