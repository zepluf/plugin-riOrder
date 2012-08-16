<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vunguyen
 * Date: 6/21/12
 * Time: 6:58 PM
 * To change this template use File | Settings | File Templates.
 */

namespace plugins\riOrder;

class OrdersProducts{
    /**
     * TODO: if we want to make it more complicated we may create an OrdersProduct class that will be our object
     **/
    public function findCustomerOrdersProducts($customers_id){
        global $db;
        $sql = "SELECT * FROM " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o
            WHERE o.customers_id = :customers_id AND o.orders_id = op.orders_id";
        $sql = $db->bindVars($sql, ':customers_id', $customers_id, 'integer');
        $products = $db->Execute($sql);

        $result = array();
        while(!$products->EOF){
            $result[] = $products->fields;
            $products->MoveNext();
        }

        return $result;
    }
}