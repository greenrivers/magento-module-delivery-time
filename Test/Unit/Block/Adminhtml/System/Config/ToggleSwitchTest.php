<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Test\Unit\Block\Adminhtml\System\Config;

use Greenrivers\DeliveryTime\Helper\Compatibility;
use Magento\Framework\Data\Form\Element\AbstractElement;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Greenrivers\DeliveryTime\Block\Adminhtml\System\Config\ToggleSwitch;
use Greenrivers\DeliveryTime\Helper\Config;
use Greenrivers\DeliveryTime\Test\Unit\Traits\TraitObjectManager;
use Greenrivers\DeliveryTime\Test\Unit\Traits\TraitReflectionClass;

class ToggleSwitchTest extends TestCase
{
    use TraitObjectManager;
    use TraitReflectionClass;

    /** @var ToggleSwitch */
    private $toggleSwitch;

    /** @var Config|MockObject */
    private $configMock;

    /** @var Compatibility|MockObject */
    private $compatibilityMock;

    /** @var AbstractElement|MockObject */
    private $elementMock;

    protected function setUp(): void
    {
        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->compatibilityMock = $this->getMockBuilder(Compatibility::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->elementMock = $this->getMockBuilder(AbstractElement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->toggleSwitch = $this->getObjectManager()->getObject(ToggleSwitch::class);

        $properties = [
            $this->configMock,
            $this->compatibilityMock,
            $this->elementMock
        ];
        $this->toggleSwitch = $this->getObjectManager()->getObject(ToggleSwitch::class, $properties);
        $this->setAccessibleProperties($this->toggleSwitch, $properties);
    }

    /**
     * @covers ToggleSwitch::getComponent()
     */
    public function testGetComponent()
    {
        $this->elementMock->expects(self::exactly(3))
            ->method('getHtmlId')
            ->willReturn('delivery_time_frontend_sort');
        $this->elementMock->expects(self::exactly(3))
            ->method('getName')
            ->willReturn('groups[frontend][fields][sort][value]');
        $this->configMock->expects(self::exactly(3))
            ->method('getSortConfig')
            ->willReturn(true);

        $this->toggleSwitch->setElement($this->elementMock);

        $this->assertEquals(
            ['id' => 'delivery-time-frontend-sort', 'name' => 'groups[frontend][fields][sort][value]', 'value' => true],
            $this->toggleSwitch->getComponent()
        );
        $this->assertCount(3, $this->toggleSwitch->getComponent());
        $this->assertArrayHasKey('value', $this->toggleSwitch->getComponent());
    }
}
