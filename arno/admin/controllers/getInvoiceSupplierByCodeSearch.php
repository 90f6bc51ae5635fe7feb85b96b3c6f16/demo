<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceSupplierModel.php');  
$invoice_supplier_code_gen = $_POST['invoice_supplier_code_gen']; 
$purchase_model = new InvoiceSupplierModel; 
$purchase = $purchase_model->getInvoiceSupplierByCodeGen($invoice_supplier_code_gen ); 
echo json_encode($purchase);

?>