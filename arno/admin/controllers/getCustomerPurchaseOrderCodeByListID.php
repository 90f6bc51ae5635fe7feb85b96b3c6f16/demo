<?php  

require_once('../../models/CustomerPurchaseOrderModel.php'); 
 
$customer_purchase_order_model = new CustomerPurchaseOrderModel;
 
$customer_purchase_order_list_id = json_decode($_POST['customer_purchase_order_list_id'],true);
$data=$customer_purchase_order_model->getCustomerPurchaseOrderCodeByListID($customer_purchase_order_list_id);
 
echo $data;
?>