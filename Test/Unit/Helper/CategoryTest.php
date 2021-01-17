<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Test\Unit\Helper;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\App\Http;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Greenrivers\DeliveryTime\Helper\Category;
use Greenrivers\DeliveryTime\Helper\Config;
use Greenrivers\DeliveryTime\Test\Unit\Traits\TraitObjectManager;
use Greenrivers\DeliveryTime\Test\Unit\Traits\TraitReflectionClass;

class CategoryTest extends TestCase
{
    use TraitObjectManager;
    use TraitReflectionClass;

    /** @var Category */
    private $category;

    /** @var Config|MockObject */
    private $configMock;

    /** @var SearchCriteriaBuilder|MockObject */
    private $searchCriteriaBuilderMock;

    /** @var ProductRepositoryInterface|MockObject */
    private $productRepositoryMock;

    /** @var Http|MockObject */
    private $requestMock;

    /** @var UrlInterface|MockObject */
    private $urlMock;

    /** @var LoggerInterface|MockObject */
    private $loggerMock;

    protected function setUp(): void
    {
        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->searchCriteriaBuilderMock = $this->getMockBuilder(SearchCriteriaBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->productRepositoryMock = $this->getMockBuilder(ProductRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->requestMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->urlMock = $this->getMockBuilder(UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $properties = [
            $this->configMock,
            $this->searchCriteriaBuilderMock,
            $this->productRepositoryMock,
            $this->requestMock,
            $this->urlMock,
            $this->loggerMock
        ];
        $this->category = $this->getObjectManager()->getObject(Category::class, $properties);
        $this->setAccessibleProperties($this->category, $properties);
    }

    /**
     * @covers Category::getMaxValue
     */
    public function testGetMaxValue()
    {
        $products = [];
        for ($i = 0; $i < 3; $i++) {
            $product = $this->getMockBuilder(ProductInterface::class)
                ->disableOriginalConstructor()
                ->setMethods(['getDeliveryTimeMax'])
                ->getMockForAbstractClass();
            $product->expects(self::atLeastOnce())
                ->method('getDeliveryTimeMax')
                ->willReturn($i + 1);

            $products[] = $product;
        }
        $searchCriteriaMock = $this->getMockBuilder(SearchCriteria::class)
            ->disableOriginalConstructor()
            ->getMock();
        $searchResultsMock = $this->getMockBuilder(SearchResultsInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->searchCriteriaBuilderMock->expects(self::once())
            ->method('addFilter')
            ->willReturnSelf();
        $this->searchCriteriaBuilderMock->expects(self::once())
            ->method('create')
            ->willReturn($searchCriteriaMock);
        $this->productRepositoryMock->expects(self::once())
            ->method('getList')
            ->with($searchCriteriaMock)
            ->willReturn($searchResultsMock);
        $searchResultsMock->expects(self::once())
            ->method('getItems')
            ->willReturn($products);

        $this->assertEquals(3, $this->category->getMaxValue(1));
    }

    /**
     * @covers Category::isDeliveryTime
     */
    public function testIsDeliveryTime()
    {
        $itemMock = $this->getMockBuilder(Item::class)
            ->disableOriginalConstructor()
            ->setMethods(['getFilter'])
            ->getMock();
        $filterMock = $this->getMockBuilder(AbstractFilter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $attributeMock = $this->getMockBuilder(Attribute::class)
            ->disableOriginalConstructor()
            ->getMock();

        $itemMock->expects(self::once())
            ->method('getFilter')
            ->willReturn($filterMock);
        $filterMock->expects(self::once())
            ->method('getAttributeModel')
            ->willReturn($attributeMock);
        $attributeMock->expects(self::once())
            ->method('getAttributeCode')
            ->willReturn('delivery_time_max');

        $this->assertTrue($this->category->isDeliveryTime($itemMock));
    }
}
