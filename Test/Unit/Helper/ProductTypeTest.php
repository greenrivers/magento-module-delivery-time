<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Test\Unit\Helper;

use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Quote\Model\Quote\Item\Option;
use Magento\Sales\Model\Order\Item;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Greenrivers\DeliveryTime\Helper\ProductType;
use Greenrivers\DeliveryTime\Test\Unit\Traits\TraitObjectManager;

class ProductTypeTest extends TestCase
{
    use TraitObjectManager;

    /** @var ProductType */
    private $productType;

    /** @var Product */
    private $product;

    /** @var QuoteItem|MockObject */
    private $quoteItemMock;

    /** @var Item|MockObject */
    private $itemMock;

    /** @var Option|MockObject */
    private $optionMock;

    protected function setUp(): void
    {
        $this->quoteItemMock = $this->getMockBuilder(QuoteItem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->itemMock = $this->getMockBuilder(Item::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->optionMock = $this->getMockBuilder(Option::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->product = $this->getObjectManager()->getObject(Product::class);
        $this->productType = $this->getObjectManager()->getObject(ProductType::class);
    }

    /**
     * @covers ProductType::getProductFromQuoteItem
     */
    public function testGetProductFromQuoteItem()
    {
        $this->quoteItemMock->expects(self::exactly(2))
            ->method('getProductType')
            ->willReturn(Product\Type::DEFAULT_TYPE);
        $this->quoteItemMock->expects(self::exactly(2))
            ->method('getProduct')
            ->willReturn($this->product);

        $this->assertEquals($this->product, $this->productType->getProductFromQuoteItem($this->quoteItemMock));
        $this->assertInstanceOf(Product::class, $this->productType->getProductFromQuoteItem($this->quoteItemMock));
    }

    /**
     * @covers ProductType::getProductFromQuoteItem
     */
    public function testGetProductFromQuoteItemConfigurable()
    {
        $this->quoteItemMock->expects(self::exactly(2))
            ->method('getProductType')
            ->willReturn(Configurable::TYPE_CODE);
        $this->quoteItemMock->expects(self::exactly(2))
            ->method('getOptionByCode')
            ->with('simple_product')
            ->willReturn($this->optionMock);
        $this->optionMock->expects(self::exactly(2))
            ->method('getProduct')
            ->willReturn($this->product);

        $this->assertEquals($this->product, $this->productType->getProductFromQuoteItem($this->quoteItemMock));
        $this->assertInstanceOf(Product::class, $this->productType->getProductFromQuoteItem($this->quoteItemMock));
    }

    /**
     * @covers ProductType::getProductFromItem
     */
    public function testGetProductFromItem()
    {
        $this->itemMock->expects(self::exactly(2))
            ->method('getProductType')
            ->willReturn(Product\Type::DEFAULT_TYPE);
        $this->itemMock->expects(self::exactly(2))
            ->method('getProduct')
            ->willReturn($this->product);

        $this->assertEquals($this->product, $this->productType->getProductFromItem($this->itemMock));
        $this->assertInstanceOf(Product::class, $this->productType->getProductFromItem($this->itemMock));
    }

    /**
     * @covers ProductType::getProductFromItem
     */
    public function testGetProductFromItemConfigurable()
    {
        $items = [$this->itemMock, $this->itemMock, $this->itemMock];

        $this->itemMock->expects(self::exactly(2))
            ->method('getProductType')
            ->willReturn(Configurable::TYPE_CODE);
        $this->itemMock->expects(self::exactly(2))
            ->method('getChildrenItems')
            ->willReturn($items);
        $this->itemMock->expects(self::exactly(2))
            ->method('getProduct')
            ->willReturn($this->product);

        $this->assertEquals($this->product, $this->productType->getProductFromItem($this->itemMock));
        $this->assertInstanceOf(Product::class, $this->productType->getProductFromItem($this->itemMock));
    }
}
