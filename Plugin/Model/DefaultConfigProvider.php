<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\Model;

use Magento\Checkout\Model\DefaultConfigProvider as Subject;
use Magento\Framework\App\Request\Http as Request;
use Unexpected\DeliveryTime\Helper\Render;

class DefaultConfigProvider
{
    /** @var Render */
    private $render;

    /** @var Request */
    private $request;

    /**
     * DefaultConfigProvider constructor.
     * @param Render $render
     * @param Request $request
     */
    public function __construct(Render $render, Request $request)
    {
        $this->render = $render;
        $this->request = $request;
    }

    /**
     * @param Subject $subject
     * @param array $result
     * @return array
     */
    public function afterGetConfig(Subject $subject, array $result): array
    {
        $layout = $this->request->getFullActionName();
        if ($this->render->isEnabled($layout)) {
            $items = $result['totalsData']['items'];
            for ($i = 0; $i < count($items); $i++) {
                $product = $result['quoteItemData'][$i]['product'];
                $items[$i]['delivery_time'] = $this->render->getFromProductArray($product);
            }
            $result['totalsData']['items'] = $items;
        }
        return $result;
    }
}
