<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Greenrivers\DeliveryTime\Helper\Config;
use Greenrivers\DeliveryTime\Test\Unit\Traits\TraitObjectManager;

class ConfigTest extends TestCase
{
    use TraitObjectManager;

    /** @var Config */
    private $config;

    /** @var ScopeConfigInterface|MockObject */
    private $scopeConfigMock;

    protected function setUp(): void
    {
        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->config = $this->getObjectManager()->getObject(
            Config::class,
            ['scopeConfig' => $this->scopeConfigMock]
        );
    }

    /**
     * @covers Config::getEnabledConfig
     */
    public function testGetEnabledConfig()
    {
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('delivery_time/general/enabled', 'store')
            ->willReturn(true);

        $this->assertTrue($this->config->getEnabledConfig());
    }

    /**
     * @covers Config::getDateUnitConfig
     */
    public function testGetDateUnitConfig()
    {
        $value = 'days';
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('delivery_time/backend/date_unit', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getDateUnitConfig());
    }

    /**
     * @covers Config::getMinScaleConfig
     */
    public function testGetMinScaleConfig()
    {
        $value = 1;
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('delivery_time/backend/min_scale', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getMinScaleConfig());
    }

    /**
     * @covers Config::getMaxScaleConfig
     */
    public function testGetMaxScaleConfig()
    {
        $value = 100;
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('delivery_time/backend/max_scale', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getMaxScaleConfig());
    }

    /**
     * @covers Config::getScaleStepConfig
     */
    public function testGetScaleStepConfig()
    {
        $value = 10;
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('delivery_time/backend/scale_step', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getScaleStepConfig());
    }

    /**
     * @covers Config::getLabelConfig
     */
    public function testGetLabelConfig()
    {
        $value = 'Delivery time';
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('delivery_time/frontend/label', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getLabelConfig());
    }

    /**
     * @covers Config::getSortConfig
     */
    public function testGetSortConfig()
    {
        $value = true;
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('delivery_time/frontend/sort', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getSortConfig());
        $this->assertTrue($this->config->getSortConfig());
    }

    /**
     * @covers Config::getFilterConfig
     */
    public function testGetFilterConfig()
    {
        $value = true;
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('delivery_time/frontend/filter', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getFilterConfig());
        $this->assertTrue($this->config->getFilterConfig());
    }

    /**
     * @covers Config::getVisibilityConfig
     */
    public function testGetVisibilityConfig()
    {
        $value = 'page1,page2,page3';
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('delivery_time/frontend/visibility', 'store')
            ->willReturn($value);

        $this->assertEquals(['page1', 'page2', 'page3'], $this->config->getVisibilityConfig());
        $this->assertCount(3, $this->config->getVisibilityConfig());
    }
}
