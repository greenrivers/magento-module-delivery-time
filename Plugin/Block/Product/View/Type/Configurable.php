<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_DeliveryTime
 */

namespace Greenrivers\DeliveryTime\Plugin\Block\Product\View\Type;

use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable as Subject;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableProduct;
use Magento\Framework\Serialize\Serializer\Json;
use Greenrivers\DeliveryTime\Helper\Render;

class Configurable
{
    /** @var Json */
    private $json;

    /** @var Render */
    private $render;

    /**
     * Configurable constructor.
     * @param Json $json
     * @param Render $render
     */
    public function __construct(Json $json, Render $render)
    {
        $this->json = $json;
        $this->render = $render;
    }

    /**
     * @param Subject $subject
     * @param string $result
     * @return string
     */
    public function afterGetJsonConfig(Subject $subject, string $result): string
    {
        $layout = $subject->getRequest()->getFullActionName();
        $product = $subject->getProduct();

        if ($this->render->canShowOnProduct($layout, $product)) {
            $jsonResult = $this->json->unserialize($result);
            $jsonResult['deliveryTime'][ConfigurableProduct::TYPE_CODE] = $this->render->getFromProduct($product);
            $childProducts = $subject->getAllowProducts();
            foreach ($childProducts as $childProduct) {
                $id = $childProduct->getId();
                $jsonResult['deliveryTime'][$id] = $this->render->getFromProduct($childProduct);
            }
            $result = $this->json->serialize($jsonResult);
        }

        return $result;
    }
}
