<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_DeliveryTime
 */

namespace GreenRivers\DeliveryTime\Test\Unit\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use GreenRivers\DeliveryTime\Block\Adminhtml\System\Config\ToggleSwitch;
use GreenRivers\DeliveryTime\Helper\Config;
use GreenRivers\DeliveryTime\Test\Unit\Traits\TraitObjectManager;
use GreenRivers\DeliveryTime\Test\Unit\Traits\TraitReflectionClass;

class ToggleSwitchTest extends TestCase
{
    use TraitObjectManager;
    use TraitReflectionClass;

    /** @var ToggleSwitch */
    private $toggleSwitch;

    /** @var Config|PHPUnit_Framework_MockObject_MockObject */
    private $configMock;

    /** @var AbstractElement|PHPUnit_Framework_MockObject_MockObject */
    private $elementMock;

    protected function setUp()
    {
        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->elementMock = $this->getMockBuilder(AbstractElement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->toggleSwitch = $this->getObjectManager()->getObject(ToggleSwitch::class);

        $properties = [$this->configMock, $this->elementMock];
        $this->toggleSwitch = $this->getObjectManager()->getObject(ToggleSwitch::class, $properties);
        $this->setAccessibleProperties($this->toggleSwitch, $properties);
    }

    /**
     * @covers ToggleSwitch::getComponent()
     */
    public function testGetComponent()
    {
        $this->elementMock->expects(self::exactly(4))
            ->method('getHtmlId')
            ->willReturn('delivery_time_frontend_sort');
        $this->elementMock->expects(self::exactly(4))
            ->method('getName')
            ->willReturn('groups[frontend][fields][sort][value]');
        $this->configMock->expects(self::exactly(4))
            ->method('getSortConfig')
            ->willReturn(true);

        $this->toggleSwitch->setElement($this->elementMock);

        $this->assertEquals(
            ['id' => 'delivery-time-frontend-sort', 'name' => 'groups[frontend][fields][sort][value]', 'value' => true],
            $this->toggleSwitch->getComponent()
        );
        $this->assertCount(3, $this->toggleSwitch->getComponent());
        $this->assertArrayHasKey('value', $this->toggleSwitch->getComponent());
        $this->assertInternalType(IsType::TYPE_ARRAY, $this->toggleSwitch->getComponent());
    }
}
