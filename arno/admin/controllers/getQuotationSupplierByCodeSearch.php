<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/QuotationSupplierModel.php');  
$quotation_supplier_code_gen = $_POST['quotation_supplier_code_gen']; 
$model = new QuotationSupplierModel; 
$data = $model->getQuotationSupplierByCodeGen($quotation_supplier_code_gen ); 
echo json_encode($data);

?>