<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Test\Unit\Helper;

use Magento\Catalog\Model\Product;
use Magento\Sales\Model\Order\Item;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Greenrivers\DeliveryTime\Api\Data\DeliveryTimeInterface;
use Greenrivers\DeliveryTime\Api\DeliveryTimeRepositoryInterface;
use Greenrivers\DeliveryTime\Helper\Config;
use Greenrivers\DeliveryTime\Helper\Render;
use Greenrivers\DeliveryTime\Test\Unit\Traits\TraitObjectManager;
use Greenrivers\DeliveryTime\Test\Unit\Traits\TraitReflectionClass;

class RenderTest extends TestCase
{
    use TraitObjectManager;
    use TraitReflectionClass;

    /** @var Render */
    private $render;

    /** @var Config|MockObject */
    private $configMock;

    /** @var DeliveryTimeRepositoryInterface|MockObject */
    private $deliveryTimeRepositoryMock;

    /** @var LoggerInterface|MockObject */
    private $loggerMock;

    /** @var Product|MockObject */
    private $productMock;

    /** @var Item|MockObject */
    private $itemMock;

    /** @var DeliveryTimeInterface|MockObject */
    private $deliveryTimeMock;

    protected function setUp(): void
    {
        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['getDateUnitConfig', 'getEnabledConfig', 'getVisibilityConfig', 'getLabelConfig'])
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
        $this->productMock->expects(self::once())
            ->method('getDeliveryTimeType')
            ->willReturn(2);
        $this->productMock->expects(self::once())
            ->method('getDeliveryTimeMin')
            ->willReturn(1);
        $this->productMock->expects(self::once())
            ->method('getDeliveryTimeMax')
            ->willReturn(10);
        $this->configMock->expects(self::once())
            ->method('getDateUnitConfig')
            ->willReturn('hours');

        $this->assertEquals('From 1 to 10 hours', $this->render->getFromProduct($this->productMock));
    }

    /**
     * @covers Render::getFromOrderItem
     */
    public function testGetFromOrderItem()
    {
        $content = 'From 5 to 20 days';

        $this->itemMock->expects(self::once())
            ->method('getId')
            ->willReturn(1);
        $this->deliveryTimeRepositoryMock->expects(self::once())
            ->method('getByOrderItemId')
            ->with(1)
            ->willReturn($this->deliveryTimeMock);
        $this->deliveryTimeMock->expects(self::once())
            ->method('getContent')
            ->willReturn($content);

        $this->assertEquals($content, $this->render->getFromOrderItem($this->itemMock));
    }

    /**
     * @covers Render::canShowOnProduct
     */
    public function testCanShowOnProduct()
    {
        $this->configMock->expects(self::once())
            ->method('getEnabledConfig')
            ->willReturn(true);
        $this->configMock->expects(self::once())
            ->method('getVisibilityConfig')
            ->willReturn(['page1', 'page2', 'page3']);
        $this->productMock->expects(self::exactly(2))
            ->method('getDeliveryTimeType')
            ->willReturn(1);

        $this->assertTrue($this->render->canShowOnProduct('page1', $this->productMock));
    }

    /**
     * @covers Render::canShowOnProducts
     */
    public function testCanShowOnProducts()
    {
        $this->configMock->expects(self::once())
            ->method('getEnabledConfig')
            ->willReturn(true);
        $this->configMock->expects(self::once())
            ->method('getVisibilityConfig')
            ->willReturn(['page1', 'page2', 'page3']);
        $this->itemMock->expects(self::once())
            ->method('getProduct')
            ->willReturn($this->productMock);
        $this->productMock->expects(self::exactly(2))
            ->method('getDeliveryTimeType')
            ->willReturn(2);

        $this->assertTrue($this->render->canShowOnProducts('page2', [$this->itemMock, $this->itemMock]));
    }

    /**
     * @covers Render::canShowOnItems
     */
    public function testCanShowOnItems()
    {
        $this->configMock->expects(self::once())
            ->method('getEnabledConfig')
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
        $this->configMock->expects(self::once())
            ->method('getLabelConfig')
            ->willReturn($value);

        $this->assertEquals($value, $this->render->getLabel());
    }
}
