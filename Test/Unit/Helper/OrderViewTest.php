<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Greenrivers\DeliveryTime\Helper\OrderView;
use Greenrivers\DeliveryTime\Test\Unit\Traits\TraitObjectManager;

class OrderViewTest extends TestCase
{
    use TraitObjectManager;

    /** @var OrderView */
    private $orderView;

    protected function setUp(): void
    {
        $this->orderView = $this->getObjectManager()->getObject(OrderView::class);
    }

    /**
     * @covers OrderView::addColumn
     */
    public function testGetAddColumn()
    {
        $columns = ['column1' => 10, 'column2' => 20, 'column3' => 30];
        $column = ['columnX' => 25];
        $position = 2;

        $result = $this->orderView->addColumn($columns, $column, $position);

        $this->assertEquals(['column1' => 10, 'column2' => 20, 'columnX' => 25, 'column3' => 30], $result);
        $this->assertArrayHasKey('columnX', $result);
        $this->assertCount(4, $result);
    }
}
