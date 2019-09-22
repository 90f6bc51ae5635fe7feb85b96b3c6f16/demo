<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceCustomerModel.php');
$customer_purchase_order_code = $_POST['customer_purchase_order_code']; 

$purchase_model = new InvoiceCustomerModel;

$purchase = $purchase_model->getCustomerPurchaseOrderByCode( $customer_purchase_order_code );

echo json_encode($purchase);

?>