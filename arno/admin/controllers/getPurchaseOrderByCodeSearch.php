<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/PurchaseOrderModel.php');
$purchase_order_code = $_POST['purchase_order_code']; 

$purchase_model = new PurchaseOrderModel;

$purchase = $purchase_model->getPurchaseOrderByCode($purchase_order_code );

echo json_encode($purchase);

?>