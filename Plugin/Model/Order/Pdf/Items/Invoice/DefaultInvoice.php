<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\Model\Order\Pdf\Items\Invoice;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Sales\Model\Order\Pdf\Items\Invoice\DefaultInvoice as Subject;
use Magento\Sales\Model\Order\Invoice\Item;
use Psr\Log\LoggerInterface;
use Unexpected\DeliveryTime\Api\DeliveryTimeRepositoryInterface;
use Unexpected\DeliveryTime\Helper\Render;

class DefaultInvoice
{
    /** @var StringUtils */
    private $string;

    /** @var FilterManager */
    private $filterManager;

    /** @var DeliveryTimeRepositoryInterface */
    private $deliveryTimeRepository;

    /** @var Render */
    private $render;

    /** @var Http */
    private $request;

    /** @var LoggerInterface */
    private $logger;

    /**
     * DefaultInvoice constructor.
     * @param StringUtils $string
     * @param FilterManager $filterManager
     * @param DeliveryTimeRepositoryInterface $deliveryTimeRepository
     * @param Render $render
     * @param Http $request
     * @param LoggerInterface $logger
     */
    public function __construct(
        StringUtils $string,
        FilterManager $filterManager,
        DeliveryTimeRepositoryInterface $deliveryTimeRepository,
        Render $render,
        Http $request,
        LoggerInterface $logger
    ) {
        $this->string = $string;
        $this->filterManager = $filterManager;
        $this->deliveryTimeRepository = $deliveryTimeRepository;
        $this->render = $render;
        $this->request = $request;
        $this->logger = $logger;
    }

    /**
     * @param Subject $subject
     * @param callable $proceed
     */
    public function aroundDraw(Subject $subject, callable $proceed): void
    {
        try {
            $order = $subject->getOrder();
            /** @var Item $item */
            $item = $subject->getItem();
            $pdf = $subject->getPdf();
            $page = $subject->getPage();
            $orderItem = $item->getOrderItem();
            $orderItems = $order->getAllItems();
            $layout = $this->request->getFullActionName();
            $lines = [];

            // draw Product name
            $lines[0] = [
                [
                    // phpcs:ignore Magento2.Functions.DiscouragedFunction
                    'text' => $this->string->split(html_entity_decode($item->getName()), 35, true, true),
                    'feed' => 35
                ]
            ];

            // draw SKU
            $lines[0][] = [
                // phpcs:ignore Magento2.Functions.DiscouragedFunction
                'text' => $this->string->split(html_entity_decode($subject->getSku($item)), 17),
                'feed' => 290,
                'align' => 'right',
            ];

            // draw Delivery Time
            if ($this->render->canShowOnItems($layout, $orderItems)) {
                $deliveryTime = $this->deliveryTimeRepository->getByOrderItemId($orderItem->getId());
                $lines[0][] = [
                    'text' => $deliveryTime->getContent(),
                    'feed' => 220,
                    'align' => 'right'
                ];
            }

            // draw QTY
            $lines[0][] = ['text' => $item->getQty() * 1, 'feed' => 435, 'align' => 'right'];

            // draw item Prices
            $i = 0;
            $prices = $subject->getItemPricesForDisplay();
            $feedPrice = 395;
            $feedSubtotal = $feedPrice + 170;
            foreach ($prices as $priceData) {
                if (isset($priceData['label'])) {
                    // draw Price label
                    $lines[$i][] = ['text' => $priceData['label'], 'feed' => $feedPrice, 'align' => 'right'];
                    // draw Subtotal label
                    $lines[$i][] = ['text' => $priceData['label'], 'feed' => $feedSubtotal, 'align' => 'right'];
                    $i++;
                }
                // draw Price
                $lines[$i][] = [
                    'text' => $priceData['price'],
                    'feed' => $feedPrice,
                    'font' => 'bold',
                    'align' => 'right',
                ];
                // draw Subtotal
                $lines[$i][] = [
                    'text' => $priceData['subtotal'],
                    'feed' => $feedSubtotal,
                    'font' => 'bold',
                    'align' => 'right',
                ];
                $i++;
            }

            // draw Tax
            $lines[0][] = [
                'text' => $order->formatPriceTxt($item->getTaxAmount()),
                'feed' => 495,
                'font' => 'bold',
                'align' => 'right',
            ];

            // custom options
            $options = $subject->getItemOptions();
            if ($options) {
                foreach ($options as $option) {
                    // draw options label
                    $lines[][] = [
                        'text' => $this->string->split($this->filterManager->stripTags($option['label']), 40, true, true),
                        'font' => 'italic',
                        'feed' => 35,
                    ];

                    // Checking whether option value is not null
                    if ($option['value'] !== null) {
                        if (isset($option['print_value'])) {
                            $printValue = $option['print_value'];
                        } else {
                            $printValue = $this->filterManager->stripTags($option['value']);
                        }
                        $values = explode(', ', $printValue);
                        foreach ($values as $value) {
                            $lines[][] = ['text' => $this->string->split($value, 30, true, true), 'feed' => 40];
                        }
                    }
                }
            }

            $lineBlock = ['lines' => $lines, 'height' => 20];
            $page = $pdf->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
            $subject->setPage($page);
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
