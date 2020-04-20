<?php

namespace Unexpected\DeliveryTime\Observer;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SaveFields implements ObserverInterface
{
    /** @var Http */
    private $request;

    /**
     * SaveFields constructor.
     * @param Http $request
     */
    public function __construct(Http $request)
    {
        $this->request = $request;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer): void
    {
        /** @var ProductInterface $product */
        $product = $observer->getProduct();
    }
}
