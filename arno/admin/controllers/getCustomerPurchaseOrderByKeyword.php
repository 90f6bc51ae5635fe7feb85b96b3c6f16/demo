<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceCustomerModel.php');
$keyword = $_GET['keyword']; 

$purchase_model = new InvoiceCustomerModel;

$purchase = $purchase_model->getCustomerPurchaseOrder( $keyword );

echo json_encode($purchase);

?>