<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Test\Unit\Traits;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

trait TraitObjectManager
{
    /** @var ObjectManager */
    private $objectManager;

    /**
     * @return ObjectManager
     */
    private function getObjectManager(): ObjectManager
    {
        if (null === $this->objectManager) {
            $this->objectManager = new ObjectManager($this);
        }

        return $this->objectManager;
    }
}
