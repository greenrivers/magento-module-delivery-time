<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\Model\Order\Pdf\Items\Creditmemo;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Sales\Model\Order\Creditmemo\Item;
use Magento\Sales\Model\Order\Pdf\Items\Creditmemo\DefaultCreditmemo as Subject;
use Magento\Framework\App\Request\Http;
use Psr\Log\LoggerInterface;
use Unexpected\DeliveryTime\Api\DeliveryTimeRepositoryInterface;
use Unexpected\DeliveryTime\Helper\Render;

class DefaultCreditmemo
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
     * DefaultShipment constructor.
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
            $orderItem = $item->getOrderItem();
            $orderItems = $order->getAllItems();
            $pdf = $subject->getPdf();
            $page = $subject->getPage();
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

            // draw Delivery Time
            if ($this->render->canShowOnItems($layout, $orderItems)) {
                $deliveryTime = $this->deliveryTimeRepository->getByOrderItemId($orderItem->getId());
                $lines[0][] = [
                    'text' => $deliveryTime->getContent(),
                    'feed' => 140
                ];
            }

            // draw SKU
            $lines[0][] = [
                // phpcs:ignore Magento2.Functions.DiscouragedFunction
                'text' => $this->string->split(html_entity_decode($subject->getSku($item)), 17),
                'feed' => 255,
                'align' => 'right',
            ];

            // draw Total (ex)
            $lines[0][] = [
                'text' => $order->formatPriceTxt($item->getRowTotal()),
                'feed' => 330,
                'font' => 'bold',
                'align' => 'right',
            ];

            // draw Discount
            $lines[0][] = [
                'text' => $order->formatPriceTxt(-$item->getDiscountAmount()),
                'feed' => 380,
                'font' => 'bold',
                'align' => 'right',
            ];

            // draw QTY
            $lines[0][] = ['text' => $item->getQty() * 1, 'feed' => 445, 'font' => 'bold', 'align' => 'right'];

            // draw Tax
            $lines[0][] = [
                'text' => $order->formatPriceTxt($item->getTaxAmount()),
                'feed' => 495,
                'font' => 'bold',
                'align' => 'right',
            ];

            // draw Total (inc)
            $subtotal = $item->getRowTotal() +
                $item->getTaxAmount() +
                $item->getDiscountTaxCompensationAmount() -
                $item->getDiscountAmount();
            $lines[0][] = [
                'text' => $order->formatPriceTxt($subtotal),
                'feed' => 565,
                'font' => 'bold',
                'align' => 'right',
            ];

            // draw options
            $options = $subject->getItemOptions();
            if ($options) {
                foreach ($options as $option) {
                    // draw options label
                    $lines[][] = [
                        'text' =>
                            $this->string->split($this->filterManager->stripTags($option['label']), 40, true, true),
                        'font' => 'italic',
                        'feed' => 35,
                    ];

                    // draw options value
                    $printValue = isset(
                        $option['print_value']
                    ) ? $option['print_value'] : $this->filterManager->stripTags(
                        $option['value']
                    );
                    $lines[][] = ['text' => $this->string->split($printValue, 30, true, true), 'feed' => 40];
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
