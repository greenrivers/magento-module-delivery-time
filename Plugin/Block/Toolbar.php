<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_DeliveryTime
 */

namespace Unexpected\DeliveryTime\Plugin\Block;

use Magento\Catalog\Block\Product\ProductList\Toolbar as Subject;
use Zend_Db_Expr;

class Toolbar
{
    /**
     * @param Subject $subject
     * @param Subject $result
     * @return Subject
     */
    public function afterSetCollection(Subject $subject, Subject $result): Subject
    {
        $currentOrder = $subject->getCurrentOrder();

        $cond = 'MAX(
                                        CASE WHEN catalog_product_entity_int.attribute_id = 186
                                            THEN catalog_product_entity_int.value END
                                        )';
        $max = 'MAX(
                                        CASE WHEN catalog_product_entity_int.attribute_id = 185
                                            THEN catalog_product_entity_int.value END
                                        )';
        $min = 'MAX(
                                        CASE WHEN catalog_product_entity_int.attribute_id = 184
                                            THEN catalog_product_entity_int.value END
                                        )';

        if ($currentOrder) {
            if ($currentOrder == 'delivery_time') {
                $subject->getCollection()->getSelect()
                    ->join(
                        'catalog_product_entity_int',
                        "e.entity_id = catalog_product_entity_int.entity_id",
                        ['delivery_time' => 'value']
                    )
                    ->group('e.entity_id')
                    ->columns([
                        'delivery_time_type' => new Zend_Db_Expr(
                            "MAX(
                                        CASE WHEN catalog_product_entity_int.attribute_id = 186
                                            THEN catalog_product_entity_int.value END
                                        )"
                        ),
                        'delivery_time_min' => new Zend_Db_Expr(
                            "MAX(
                                        CASE WHEN catalog_product_entity_int.attribute_id = 184
                                            THEN catalog_product_entity_int.value END
                                        )"
                        ),
                        'delivery_time_max' => new Zend_Db_Expr(
                            "MAX(
                                        CASE WHEN catalog_product_entity_int.attribute_id = 185
                                            THEN catalog_product_entity_int.value END
                                        )"
                        ),
                    ])
                    ->order('delivery_time_type')
                    ->order(
                        new Zend_Db_Expr(
                            "CASE WHEN ${cond} = 0 THEN ${max}
                                            WHEN ${cond} = 1 THEN ${max}
                                            WHEN ${cond} = 2 THEN ${min} END"
                        )
                    );

//                $str = $subject->getCollection()->getSelect()->__toString();
//                var_dump($str);
//                exit();
            }
        }

        return $result;
    }

    /**
     * @param Subject $subject
     * @param int $result
     * @return int
     */
    public function afterGetTotalNum(Subject $subject, int $result): int
    {
        return count($subject->getCollection()->getAllIds());
    }
}
