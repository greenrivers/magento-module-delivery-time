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
use Unexpected\DeliveryTime\Setup\Patch\Data\AddDeliveryTimeAttributes;

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
        $type = $product->getDeliveryTimeType() ?? 0;
        $min = $product->getDeliveryTimeMin() ?? 0;
        $max = $product->getDeliveryTimeMax() ?? 0;
        return $this->mapAttributes($type, $min, $max);
    }

    /**
     * @param array $product
     * @return string
     */
    public function getFromProductArray(array $product): string
    {
        $attrs = [
            AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE,
            AddDeliveryTimeAttributes::DELIVERY_TIME_MIN,
            AddDeliveryTimeAttributes::DELIVERY_TIME_MAX
        ];
        foreach ($attrs as $attr) {
            if (!array_key_exists($attr, $product)) {
                return '';
            }
        }
        [$attrs[0] => $type, $attrs[1] => $min, $attrs[2] => $max] = $product;
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
            if ($id) {
                $deliveryTime = $this->deliveryTimeRepository->getByOrderItemId($id);
                $content = $deliveryTime->getContent();
            }
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
        }
        return $content;
    }

    /**
     * @param string $layout
     * @return bool
     */
    public function isEnabled(string $layout): bool
    {
        return $this->config->getEnableConfig() && in_array($layout, $this->config->getVisibilityConfig());
    }

    /**
     * @param Product $product
     * @param string $layout
     * @return bool
     */
    public function isEnabledOnProduct(Product $product, string $layout): bool
    {
        return $this->isEnabled($layout) && $product->getDeliveryTimeType();
    }

    /**
     * @param Item $item
     * @param string $layout
     * @return bool
     */
    public function isEnabledOnOrderItem(Item $item, string $layout): bool
    {
        $deliveryTime = false;
        try {
            $deliveryTime = $this->deliveryTimeRepository->getByOrderItemId($item->getId());
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
        }
        return $this->isEnabled($layout) && $deliveryTime;
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
            case 1:
                return __('Up to') . " {$min} {$dateUnit}";
            case 2:
                return __('From') . " {$max} {$dateUnit}";
            case 3:
                return __('From') . " {$min} {$dateUnit} " . __('To') . " {$max} {$dateUnit}";
            default:
                return '';
        }
    }
}
