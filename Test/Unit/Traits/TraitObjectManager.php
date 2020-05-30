<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Test\Unit\Traits;

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
