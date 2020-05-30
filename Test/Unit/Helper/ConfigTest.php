<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
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
            ->with(Config::XML_ENABLE_CONFIG_PATH, ScopeInterface::SCOPE_STORE)
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
            ->with(Config::XML_DATE_UNIT_CONFIG_PATH, ScopeInterface::SCOPE_STORE)
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
            ->with(Config::XML_MIN_SCALE_CONFIG_PATH, ScopeInterface::SCOPE_STORE)
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
            ->with(Config::XML_MAX_SCALE_CONFIG_PATH, ScopeInterface::SCOPE_STORE)
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
            ->with(Config::XML_SCALE_STEP_CONFIG_PATH, ScopeInterface::SCOPE_STORE)
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
            ->with(Config::XML_LABEL_CONFIG_PATH, ScopeInterface::SCOPE_STORE)
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
            ->with(Config::XML_SORT_CONFIG_PATH, ScopeInterface::SCOPE_STORE)
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
            ->with(Config::XML_FILTER_CONFIG_PATH, ScopeInterface::SCOPE_STORE)
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
            ->with(Config::XML_VISIBILITY_CONFIG_PATH, ScopeInterface::SCOPE_STORE)
            ->willReturn($value);

        $this->assertEquals(['page1', 'page2', 'page3'], $this->config->getVisibilityConfig());
        $this->assertCount(3, $this->config->getVisibilityConfig());
        $this->assertInternalType(IsType::TYPE_ARRAY, $this->config->getVisibilityConfig());
    }
}
