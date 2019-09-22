<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/QuotationSupplierModel.php'); 
$keyword = $_GET['keyword'];
$quotation_supplier_model = new QuotationSupplierModel;

$quotation_supplier = $quotation_supplier_model->getQuotationSupplierByAutoComplete($keyword);

echo json_encode($quotation_supplier);

?>