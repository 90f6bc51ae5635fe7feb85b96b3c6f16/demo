<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceSupplierModel.php');
$purchase_order_code = $_POST['invoice_supplier_code'];
// $type = $_POST['type'];
$keyword = $_GET['keyword'];
$purchase_model = new InvoiceSupplierModel;

$purchase = $purchase_model->getInvoiceSupplierByAutoComplete($keyword);

echo json_encode($purchase);

?>