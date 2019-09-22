<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/DashboardModel.php');
date_default_timezone_set('asia/bangkok');
$invoice_customer_model = new DashboardModel; 

$page_start = $_POST['limit']*10;
$page_end = 10 ;

// $invoice_customer = $invoice_customer_model->getNetPriceGroupByCustomer(date("Y"),$page_start,$page_end);
$invoice_customer = $invoice_customer_model->getNetPriceGroupByCustomerLimit($page_start,$page_end,date("Y"));
// echo $invoice_customer;
echo json_encode($invoice_customer);
?>