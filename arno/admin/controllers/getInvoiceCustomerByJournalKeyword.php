<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceCustomerModel.php');
$keyword = $_GET['keyword'];
$type = $_GET['type'];

$invoice_customer_model = new InvoiceCustomerModel;

$invoice_customer = $invoice_customer_model->getInvoiceCustomerBy($date_start,$date_end,$customer_id,$keyword,$user_id,$type);

echo json_encode($invoice_customer);

?>