<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceSupplierModel.php');
$keyword = $_GET['keyword'];
$type = $_GET['type'];

$purchase_model = new InvoiceSupplierModel;

$purchase = $purchase_model->getPurchaseOrder($type , $keyword );

echo json_encode($purchase);

?>