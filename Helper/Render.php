<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Helper;

use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\Order\Item;
use Psr\Log\LoggerInterface;
use Unexpected\DeliveryTime\Api\DeliveryTimeRepositoryInterface;

class Render
{
    /** @var Config */
    private $config;

    /** @var DeliveryTimeRepositoryInterface */
    private $deliveryTimeRepository;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Render constructor.
     * @param Config $config
     * @param DeliveryTimeRepositoryInterface $deliveryTimeRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Config $config,
        DeliveryTimeRepositoryInterface $deliveryTimeRepository,
        LoggerInterface $logger
    ) {
        $this->config = $config;
        $this->deliveryTimeRepository = $deliveryTimeRepository;
        $this->logger = $logger;
    }

    /**
     * @param Product $product
     * @return string
     */
    public function getFromProduct(Product $product): string
    {
        $type = $product->getDeliveryTimeType();
        $min = $product->getDeliveryTimeMin();
        $max = $product->getDeliveryTimeMax();
        return $this->mapAttributes($type, $min, $max);
    }

    /**
     * @param array $product
     * @return string
     */
    public function getFromProductArray(array $product): string
    {
        ['delivery_time_type' => $type, 'delivery_time_min' => $min, 'delivery_time_max' => $max] = $product;
        return $this->mapAttributes($type, $min, $max);
    }

    /**
     * @param Item $item
     * @return string
     */
    public function getFromOrderItem(Item $item): string
    {
        $id = $item->getId();
        $content = '';
        try {
            $deliveryTime = $this->deliveryTimeRepository->getById($id);
            $content = $deliveryTime->getContent();
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
        }
        return $content;
    }

    /**
     * @param int $type
     * @param int $min
     * @param int $max
     * @return string
     */
    private function mapAttributes(int $type, int $min, int $max): string
    {
        $dateUnit = $this->config->getDateUnitConfig();

        switch ($type) {
            case 0:
                return __('From') . " {$min} {$dateUnit}";
            case 1:
                return __('From') . " {$max} {$dateUnit}";
            case 2:
                return __('From') . " {$min} {$dateUnit} " . __('To') . " {$max} {$dateUnit}";
            default:
                return '';
        }
    }
}
