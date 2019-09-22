<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceSupplierModel.php');
$keyword = $_GET['keyword'];
$type = $_GET['type'];

$invoice_supplier_model = new InvoiceSupplierModel;

$invoice_supplier = $invoice_supplier_model->getInvoiceSupplierBy($date_start,$date_end,$supplier_id,$keyword,$user_id,$type);

echo json_encode($invoice_supplier);

?>