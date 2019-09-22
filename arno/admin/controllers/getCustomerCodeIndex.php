<?php 
require_once('../../models/CustomerModel.php');
$model_customer = new customerModel;
$customer = $model_customer->getCustomerCodeIndexByChar($_POST['char']);
echo $customer['customer_code'];
?>