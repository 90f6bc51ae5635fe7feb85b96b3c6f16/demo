<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/PurchaseOrderModel.php');
$purchase_order_code = $_POST['purchase_order_code'];
// $type = $_POST['type'];
$keyword = $_GET['keyword'];
$purchase_model = new PurchaseOrderModel;

$purchase = $purchase_model->getPurchaseOrderByAutoComplete($keyword);

echo json_encode($purchase);

?>