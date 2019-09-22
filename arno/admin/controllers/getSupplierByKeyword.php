<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/SupplierModel.php');
$keyword = $_GET['keyword'];

$supplier_model = new SupplierModel;

$suppliers = $supplier_model->getSupplierByKeyword($keyword);

echo json_encode($suppliers);

?>