<?php

namespace Unexpected\DeliveryTime\Plugin\Model\Order\Pdf\Items\Shipment;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Sales\Model\Order\Shipment\Item;
use Magento\Sales\Model\Order\Pdf\Items\Shipment\DefaultShipment as Subject;
use Magento\Framework\App\Request\Http;
use Psr\Log\LoggerInterface;
use Unexpected\DeliveryTime\Api\DeliveryTimeRepositoryInterface;
use Unexpected\DeliveryTime\Helper\Render;

class DefaultShipment
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
            /** @var Item $item */
            $item = $subject->getItem();
            $orderItem = $item->getOrderItem();
            $orderItems = $subject->getOrder()->getAllItems();
            $pdf = $subject->getPdf();
            $page = $subject->getPage();
            $layout = $this->request->getFullActionName();
            $lines = [];

            // draw Product name
            $lines[0] = [
                [
                    // phpcs:ignore Magento2.Functions.DiscouragedFunction
                    'text' => $this->string->split(html_entity_decode($item->getName()), 60, true, true),
                    'feed' => 100
                ]
            ];

            // draw Delivery Time
            if ($this->render->canShowOnItems($layout, $orderItems)) {
                $deliveryTime = $this->deliveryTimeRepository->getByOrderItemId($orderItem->getId());
                $lines[0][] = [
                    'text' => $deliveryTime->getContent(),
                    'feed' => 300
                ];
            }

            // draw QTY
            $lines[0][] = ['text' => $item->getQty() * 1, 'feed' => 35];

            // draw SKU
            $lines[0][] = [
                // phpcs:ignore Magento2.Functions.DiscouragedFunction
                'text' => $this->string->split(html_entity_decode($subject->getSku($item)), 25),
                'feed' => 565,
                'align' => 'right',
            ];

            // Custom options
            $options = $subject->getItemOptions();
            if ($options) {
                foreach ($options as $option) {
                    // draw options label
                    $lines[][] = [
                        'text' => $this->string->split($this->filterManager->stripTags($option['label']), 70, true, true),
                        'font' => 'italic',
                        'feed' => 110,
                    ];

                    // draw options value
                    if ($option['value'] !== null) {
                        $printValue = isset(
                            $option['print_value']
                        ) ? $option['print_value'] : $this->filterManager->stripTags(
                            $option['value']
                        );
                        $values = explode(', ', $printValue);
                        foreach ($values as $value) {
                            $lines[][] = ['text' => $this->string->split($value, 50, true, true), 'feed' => 115];
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
