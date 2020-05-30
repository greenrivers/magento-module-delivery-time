<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Test\Unit\Helper;

use Magento\Catalog\Model\Product;
use Magento\Sales\Model\Order\Item;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Log\LoggerInterface;
use Unexpected\DeliveryTime\Api\Data\DeliveryTimeInterface;
use Unexpected\DeliveryTime\Api\DeliveryTimeRepositoryInterface;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Helper\Render;
use Unexpected\DeliveryTime\Setup\Patch\Data\AddDeliveryTimeAttributes;
use Unexpected\DeliveryTime\Test\Unit\Traits\TraitObjectManager;
use Unexpected\DeliveryTime\Test\Unit\Traits\TraitReflectionClass;

class RenderTest extends TestCase
{
    use TraitObjectManager;
    use TraitReflectionClass;

    /** @var Render */
    private $render;

    /** @var Config|PHPUnit_Framework_MockObject_MockObject */
    private $configMock;

    /** @var DeliveryTimeRepositoryInterface|PHPUnit_Framework_MockObject_MockObject */
    private $deliveryTimeRepositoryMock;

    /** @var LoggerInterface|PHPUnit_Framework_MockObject_MockObject */
    private $loggerMock;

    /** @var Product|PHPUnit_Framework_MockObject_MockObject */
    private $productMock;

    /** @var Item|PHPUnit_Framework_MockObject_MockObject */
    private $itemMock;

    /** @var DeliveryTimeInterface|PHPUnit_Framework_MockObject_MockObject */
    private $deliveryTimeMock;

    protected function setUp()
    {
        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['getDateUnitConfig', 'getEnableConfig', 'getVisibilityConfig', 'getLabelConfig'])
            ->getMock();
        $this->deliveryTimeRepositoryMock = $this->getMockBuilder(DeliveryTimeRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods(['getDeliveryTimeType', 'getDeliveryTimeMin', 'getDeliveryTimeMax'])
            ->getMock();
        $this->itemMock = $this->getMockBuilder(Item::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->deliveryTimeMock = $this->getMockBuilder(DeliveryTimeInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getContent'])
            ->getMockForAbstractClass();

        $properties = [$this->configMock, $this->deliveryTimeRepositoryMock, $this->loggerMock];
        $this->render = $this->getObjectManager()->getObject(Render::class, $properties);
        $this->setAccessibleProperties($this->render, $properties);
    }

    /**
     * @covers Render::getFromProduct
     */
    public function testGetFromProduct()
    {
        $this->productMock->expects(self::exactly(2))
            ->method('getDeliveryTimeType')
            ->willReturn(AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE_RANGE_VALUE);
        $this->productMock->expects(self::exactly(2))
            ->method('getDeliveryTimeMin')
            ->willReturn(1);
        $this->productMock->expects(self::exactly(2))
            ->method('getDeliveryTimeMax')
            ->willReturn(10);
        $this->configMock->expects(self::exactly(2))
            ->method('getDateUnitConfig')
            ->willReturn('hours');

        $this->assertEquals('From 1 to 10 hours', $this->render->getFromProduct($this->productMock));
        $this->assertInternalType(IsType::TYPE_STRING, $this->render->getFromProduct($this->productMock));
    }

    /**
     * @covers Render::getFromOrderItem
     */
    public function testGetFromOrderItem()
    {
        $content = 'From 5 to 20 days';

        $this->itemMock->expects(self::exactly(2))
            ->method('getId')
            ->willReturn(1);
        $this->deliveryTimeRepositoryMock->expects(self::exactly(2))
            ->method('getByOrderItemId')
            ->with(1)
            ->willReturn($this->deliveryTimeMock);
        $this->deliveryTimeMock->expects(self::exactly(2))
            ->method('getContent')
            ->willReturn($content);

        $this->assertEquals($content, $this->render->getFromOrderItem($this->itemMock));
        $this->assertInternalType(IsType::TYPE_STRING, $this->render->getFromOrderItem($this->itemMock));
    }

    /**
     * @covers Render::canShowOnProduct
     */
    public function testCanShowOnProduct()
    {
        $this->configMock->expects(self::once())
            ->method('getEnableConfig')
            ->willReturn(true);
        $this->configMock->expects(self::once())
            ->method('getVisibilityConfig')
            ->willReturn(['page1', 'page2', 'page3']);
        $this->productMock->expects(self::exactly(2))
            ->method('getDeliveryTimeType')
            ->willReturn(AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE_TO_VALUE);

        $this->assertTrue($this->render->canShowOnProduct('page1', $this->productMock));
    }

    /**
     * @covers Render::canShowOnProducts
     */
    public function testCanShowOnProducts()
    {
        $this->configMock->expects(self::once())
            ->method('getEnableConfig')
            ->willReturn(true);
        $this->configMock->expects(self::once())
            ->method('getVisibilityConfig')
            ->willReturn(['page1', 'page2', 'page3']);
        $this->itemMock->expects(self::once())
            ->method('getProduct')
            ->willReturn($this->productMock);
        $this->productMock->expects(self::exactly(2))
            ->method('getDeliveryTimeType')
            ->willReturn(AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE_FROM_VALUE);

        $this->assertTrue($this->render->canShowOnProducts('page2', [$this->itemMock, $this->itemMock]));
    }

    /**
     * @covers Render::canShowOnItems
     */
    public function testCanShowOnItems()
    {
        $this->configMock->expects(self::once())
            ->method('getEnableConfig')
            ->willReturn(true);
        $this->configMock->expects(self::once())
            ->method('getVisibilityConfig')
            ->willReturn(['page1', 'page2', 'page3']);
        $this->itemMock->expects(self::once())
            ->method('getId')
            ->willReturn(1);
        $this->deliveryTimeRepositoryMock->expects(self::once())
            ->method('getByOrderItemId')
            ->with(1)
            ->willReturn($this->deliveryTimeMock);

        $this->assertTrue($this->render->canShowOnItems('page3', [$this->itemMock, $this->itemMock]));
    }

    /**
     * @covers Render::getLabel
     */
    public function testGetLabel()
    {
        $value = 'Delivery time';
        $this->configMock->expects(self::exactly(2))
            ->method('getLabelConfig')
            ->willReturn($value);

        $this->assertEquals($value, $this->render->getLabel());
        $this->assertInternalType(IsType::TYPE_STRING, $this->render->getLabel());
    }
}
