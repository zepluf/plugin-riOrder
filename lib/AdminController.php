<?php

namespace plugins\riOrder;

use Symfony\Component\HttpFoundation\Request;

use plugins\riSimplex\Controller;
use plugins\riPlugin\Plugin;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller{
    
    // TODO: change to use $request
    public function searchAction(Request $request){
        global $db;
        $orders_statuses = $this->getOrdersStatuses();
        
         // for the drop down
        $style_search = array();
		$style_search[] = array('id' => 1, 'text' => 'Product Name');
		$style_search[] = array('id' => 2, 'text' => 'Model Number');
		$style_search[] = array('id' => 3, 'text' => 'Face Value');
		
        $products_query_raw = "select o.orders_id as id, o.customers_name as customers_name, o.date_purchased as date_purchased, op.products_model as model, op.products_name as products_name, op.products_price as price,
     cd.categories_name as categories_name
                         from  " . TABLE_ORDERS . " o
                          LEFT JOIN " . TABLE_ORDERS_PRODUCTS . " op ON (o.orders_id = op.orders_id)
                          LEFT JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c ON (op.products_id = p2c.products_id)
                          LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON (p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$_SESSION["languages_id"] . "')";

        $where = array();

        if(isset($_GET['submit_search']) && !empty($_GET['submit_search'])) {
            switch ($_GET['style_search']){
                case '1':
                    $where[] = "op.products_name like '%" . $_GET['search'] . "%'";
                    break;
                    	
                case '2':
                    $where[] = "op.products_model like '%" . $_GET['search'] . "%'";
                    break;
                case '3':
                    $where[] = "op.products_price like '" . $_GET['search'] . "%'";
                    break;
                    /*case '4':
                     $where[] = "cd.categories_name = '%" . $_GET['search'] . "%'";
                     break;*/
            }

            if(isset($_GET['start_date'])&& !empty($_GET['start_date'])){
                $date = new \DateTime($_GET['start_date']);
                $date_string = $date->format('Y-m-d').' 00:00:00';
                $where[] = $db->bindVars(" o.date_purchased >= :date_from ", ':date_from', $date_string, 'string');
            }
            if(isset($_GET['end_date'])&& !empty($_GET['end_date'])){
                $date = new \DateTime($_GET['end_date']);
                $date_string = $date->format('Y-m-d').' 23:59:59';
                $where[] = $db->bindVars(" o.date_purchased <= :date_to ", ':date_to', $date_string, 'string');
            }
            if(isset($_GET['status'])&& (int)$_GET['status']>0){
                $where[] = $db->bindVars(" o.orders_status = :orders_status ", ':orders_status', (int)$_GET['status'], 'integer');
            }
            if(sizeof($where) > 0) {
                $products_query_raw .= " WHERE " . implode(' AND ', $where);
            }
            	
            
            //// page splitter and display each products info
            // $products_split = new splitPageResults($split_page, 20, $products_query_raw, $products_query_numrows);
            $this->view->set(array('orders' => $db->Execute($products_query_raw)));                        
            
        }
        
        $this->container->get('templating.holder')->add('main', $this->view->render('riOrder::search.php', array('orders_statuses' => $orders_statuses, 'style_search' => $style_search)));
        return $this->render('riOrder::admin_layout');
    }
    
    public function reportAction(Request $request){
        global $db;
        
        $query = "SELECT 
        	sum(ot.value) gross_sales, 
        	sum(o.order_face_total) face_total, 
        	monthname(o.date_purchased) row_month, 
        	year(o.date_purchased) row_year,
        	month(o.date_purchased) i_month,      
			(select sum(p.products_cost) FROM ".TABLE_PRODUCTS." p left join  ".TABLE_ORDERS_PRODUCTS." op 
				on op.products_id = p.products_id  WHERE op.orders_id = o.orders_id) as cost
			from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) WHERE ot.class = 'ot_total' ";
        
        if ($request->get('orders_status') > -1) {
            $query .= " AND o.orders_status =" . (int)$request->get('orders_status');
        } 
        
        if ($request->get('orders_month') > -1) {
            $query .= " AND month(o.date_purchased) = " . (int)$request->get('orders_month');
        }
        
        $query .= " group by year(o.date_purchased), month(o.date_purchased)";
        
        $query .=  " order by o.date_purchased ";

        //if ($invert) $sales_query_raw .= "asc"; else $sales_query_raw .= "desc";

        $reports = $db->Execute($query);

        $this->container->get('templating.holder')->add('main', $this->view->render('riOrder::report.php', array('reports' => $reports, 'orders_statuses' => $this->getOrdersStatuses())));
        return $this->render('riOrder::admin_layout');
    }
    
    private function getOrdersStatuses(){
        global $db;
        $orders_statuses = array();
        $orders_status_array = array();
        $orders_status = $db->Execute("select orders_status_id, orders_status_name
                                 from " . TABLE_ORDERS_STATUS . "
                                 where language_id = '" . (int)$_SESSION['languages_id'] . "'");
              
        while (!$orders_status->EOF) {
            $orders_statuses[] = array('id' => $orders_status->fields['orders_status_id'],
                               'text' => $orders_status->fields['orders_status_name'] . ' [' . $orders_status->fields['orders_status_id'] . ']');
            $orders_status_array[$orders_status->fields['orders_status_id']] = $orders_status->fields['orders_status_name'];
            $orders_status->MoveNext();
        }
        
        return $orders_statuses;
    }
}