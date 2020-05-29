<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\Block\Adminhtml\Order\View\Items\Renderer;

use Magento\Framework\App\Area;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Block\Adminhtml\Order\View\Items\Renderer\DefaultRenderer as Subject;
use Psr\Log\LoggerInterface;
use Unexpected\DeliveryTime\Helper\OrderView;
use Unexpected\DeliveryTime\Helper\Render;

class DefaultRenderer
{
    /** @var Render */
    private $render;

    /** @var OrderView */
    private $orderView;

    /** @var LoggerInterface */
    private $logger;

    /**
     * DefaultRenderer constructor.
     * @param Render $render
     * @param OrderView $orderView
     * @param LoggerInterface $logger
     */
    public function __construct(Render $render, OrderView $orderView, LoggerInterface $logger)
    {
        $this->render = $render;
        $this->orderView = $orderView;
        $this->logger = $logger;
    }

    /**
     * @param Subject $subject
     * @param string $result
     * @param DataObject $item
     * @param string $column
     * @return string
     */
    public function afterGetColumnHtml(Subject $subject, string $result, DataObject $item, string $column): string
    {
        if ($column === OrderView::DELIVERY_TIME_COLUMN) {
            $result = $this->render->getFromOrderItem($item);
        }
        return $result;
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
                    [OrderView::DELIVERY_TIME_COLUMN => 'col-delivery-time'],
                    OrderView::POSITION
                );
            }
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }
        return $result;
    }
}
