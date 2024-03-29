<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Plugin\Block\Adminhtml\Order\View;

use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Block\Adminhtml\Order\View\Items as Subject;
use Psr\Log\LoggerInterface;
use Greenrivers\DeliveryTime\Helper\OrderView;
use Greenrivers\DeliveryTime\Helper\Render;

class Items
{
    /** @var OrderView */
    private $orderView;

    /** @var Render */
    private $render;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Items constructor.
     * @param OrderView $orderView
     * @param Render $render
     * @param LoggerInterface $logger
     */
    public function __construct(OrderView $orderView, Render $render, LoggerInterface $logger)
    {
        $this->orderView = $orderView;
        $this->render = $render;
        $this->logger = $logger;
    }

    /**
     * @param Subject $subject
     * @param array $result
     * @return array
     */
    public function afterGetColumns(Subject $subject, array $result): array
    {
        try {
            $layout = $subject->getRequest()->getFullActionName() . '_' . Area::AREA_ADMINHTML;
            $order = $subject->getOrder();
            $items = $order->getItems();
            if ($this->render->canShowOnItems($layout, $items)) {
                $result = $this->orderView->addColumn(
                    $result,
                    [OrderView::DELIVERY_TIME_COLUMN => $this->render->getLabel()],
                    OrderView::POSITION
                );
            }
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }
        return $result;
    }
}
