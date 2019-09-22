<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CustomerPurchaseOrderModel.php');  
$customer_purchase_order_code_gen = $_POST['customer_purchase_order_code_gen']; 
$model = new CustomerPurchaseOrderModel; 
$data = $model->getCustomerPurchaseOrderByCodeGen($customer_purchase_order_code_gen ); 
echo json_encode($data);

?>