<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceSupplierModel.php');
$purchase_order_code = $_POST['purchase_order_code'];
$type = $_POST['type'];

$purchase_model = new InvoiceSupplierModel;

$purchase = $purchase_model->getPurchaseOrderByCode($type  , $purchase_order_code );

echo json_encode($purchase);

?>