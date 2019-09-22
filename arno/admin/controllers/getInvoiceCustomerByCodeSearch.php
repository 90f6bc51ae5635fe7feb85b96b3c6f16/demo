<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceCustomerModel.php');  
$invoice_customer_code = $_POST['invoice_customer_code']; 
$model = new InvoiceCustomerModel; 
$data = $model->getInvoiceCustomerByCodeGen($invoice_customer_code); 
echo json_encode($data);

?>