<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Unexpected\DeliveryTime\Helper\Config;
use Unexpected\DeliveryTime\Test\Unit\Traits\TraitObjectManager;

class ConfigTest extends TestCase
{
    use TraitObjectManager;

    /** @var Config */
    private $config;

    /** @var ScopeConfigInterface|PHPUnit_Framework_MockObject_MockObject */
    private $scopeConfigMock;

    protected function setUp()
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
     * @covers Config::getEnableConfig
     */
    public function testGetEnableConfig()
    {
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('delivery_time/general/enable', 'store')
            ->willReturn(true);

        $this->assertTrue($this->config->getEnableConfig());
    }

    /**
     * @covers Config::getDateUnitConfig
     */
    public function testGetDateUnitConfig()
    {
        $value = 'days';
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('delivery_time/backend/date_unit', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getDateUnitConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getDateUnitConfig());
    }

    /**
     * @covers Config::getMinScaleConfig
     */
    public function testGetMinScaleConfig()
    {
        $value = 1;
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('delivery_time/backend/min_scale', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getMinScaleConfig());
        $this->assertInternalType(IsType::TYPE_INT, $this->config->getMinScaleConfig());
    }

    /**
     * @covers Config::getMaxScaleConfig
     */
    public function testGetMaxScaleConfig()
    {
        $value = 100;
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('delivery_time/backend/max_scale', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getMaxScaleConfig());
        $this->assertInternalType(IsType::TYPE_INT, $this->config->getMaxScaleConfig());
    }

    /**
     * @covers Config::getScaleStepConfig
     */
    public function testGetScaleStepConfig()
    {
        $value = 10;
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('delivery_time/backend/scale_step', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getScaleStepConfig());
        $this->assertInternalType(IsType::TYPE_INT, $this->config->getScaleStepConfig());
    }

    /**
     * @covers Config::getLabelConfig
     */
    public function testGetLabelConfig()
    {
        $value = 'Delivery time';
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('delivery_time/frontend/label', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getLabelConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getLabelConfig());
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
        $this->scopeConfigMock->expects(self::exactly(3))
            ->method('getValue')
            ->with('delivery_time/frontend/visibility', 'store')
            ->willReturn($value);

        $this->assertEquals(['page1', 'page2', 'page3'], $this->config->getVisibilityConfig());
        $this->assertCount(3, $this->config->getVisibilityConfig());
        $this->assertInternalType(IsType::TYPE_ARRAY, $this->config->getVisibilityConfig());
    }
}
