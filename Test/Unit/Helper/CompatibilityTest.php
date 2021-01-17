<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Test\Unit\Helper;

use Greenrivers\DeliveryTime\Helper\Compatibility;
use Greenrivers\DeliveryTime\Test\Unit\Traits\TraitObjectManager;
use Greenrivers\DeliveryTime\Test\Unit\Traits\TraitReflectionClass;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ProductMetadataInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CompatibilityTest extends TestCase
{
    use TraitObjectManager;
    use TraitReflectionClass;

    /** @var Compatibility */
    private $compatibility;

    /** @var ProductMetadataInterface|MockObject */
    private $productMetaDataMock;

    /** @var ScopeConfigInterface|MockObject */
    private $scopeConfigMock;

    protected function setUp(): void
    {
        $this->productMetaDataMock = $this->getMockBuilder(ProductMetadataInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $properties = [
            $this->productMetaDataMock,
            $this->scopeConfigMock
        ];
        $this->compatibility = $this->getObjectManager()->getObject(Compatibility::class, $properties);
        $this->setAccessibleProperties($this->compatibility, $properties);
    }

    /**
     * @covers Compatibility::canSortBy
     */
    public function testCanSortBy()
    {
        $this->productMetaDataMock->expects(self::once())
            ->method('getVersion')
            ->willReturn('2.3.6');
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('catalog/search/engine', 'store')
            ->willReturn('mysql');

        $this->assertTrue($this->compatibility->canSortBy());
    }
}
