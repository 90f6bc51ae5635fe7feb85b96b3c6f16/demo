<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/PurchaseRequestModel.php');
$purchase_order_code = $_POST['purchase_request_code'];
// $type = $_POST['type'];
$keyword = $_GET['keyword'];
$purchase_model = new PurchaseRequestModel;

$purchase = $purchase_model->getPurchaseRequestByAutoComplete($keyword);

echo json_encode($purchase);

?>