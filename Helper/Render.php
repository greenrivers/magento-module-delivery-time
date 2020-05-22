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
     * @param Product $product
     * @return bool
     */
    public function canShowOnProduct(string $layout, Product $product): bool
    {
        return $this->isEnabled() && $this->isEnabledOnLayout($layout) && $this->isEnabledOnProduct($product);
    }

    /**
     * @param string $layout
     * @param array $items
     * @return bool
     */
    public function canShowOnProducts(string $layout, array $items): bool
    {
        return $this->isEnabled() && $this->isEnabledOnLayout($layout) && $this->isEnabledOnProducts($items);
    }

    /**
     * @param string $layout
     * @param array $items
     * @return bool
     */
    public function canShowOnItems(string $layout, array $items): bool
    {
        return $this->isEnabled() && $this->isEnabledOnLayout($layout) && $this->isEnabledOnItems($items);
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->config->getLabelConfig();
    }

    /**
     * @param Product $product
     * @return bool
     */
    private function isEnabledOnProduct(Product $product): bool
    {
        return $product->getDeliveryTimeType() !== null &&
            $product->getDeliveryTimeType() !== AddDeliveryTimeAttributes::DELIVERY_TIME_TYPE_NONE_VALUE;
    }

    /**
     * @param array $items
     * @return bool
     */
    private function isEnabledOnProducts(array $items): bool
    {
        foreach ($items as $item) {
            /** @var Item $item */
            $product = $item->getProduct();
            if ($this->isEnabledOnProduct($product)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Item $item
     * @return bool
     */
    private function isEnabledOnItem(Item $item): bool
    {
        $deliveryTime = false;
        try {
            $deliveryTime = $this->deliveryTimeRepository->getByOrderItemId($item->getId());
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
        }
        return $deliveryTime || 0;
    }

    /**
     * @param array $items
     * @return bool
     */
    private function isEnabledOnItems(array $items): bool
    {
        foreach ($items as $item) {
            /** @var Item $item */
            if ($this->isEnabledOnItem($item)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    private function isEnabled(): bool
    {
        return $this->config->getEnableConfig();
    }

    /**
     * @param string $layout
     * @return bool
     */
    private function isEnabledOnLayout(string $layout): bool
    {
        return in_array($layout, $this->config->getVisibilityConfig());
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
                return __('To') . " {$min} {$dateUnit}";
            case 1:
                return __('From') . " {$min} {$dateUnit} " . __('To') . " {$max} {$dateUnit}";
            case 2:
                return __('From') . " {$max} {$dateUnit}";
            default:
                return '';
        }
    }
}
