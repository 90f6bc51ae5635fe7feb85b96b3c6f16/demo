<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/PurchaseRequestModel.php');
$purchase_request_code = $_POST['purchase_request_code']; 

$purchase_model = new PurchaseRequestModel;

$purchase = $purchase_model->getPurchaseRequestByCode( $purchase_request_code );

echo json_encode($purchase);

?>