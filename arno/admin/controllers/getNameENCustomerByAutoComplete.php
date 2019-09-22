<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CustomerModel.php');

$model = new CustomerModel;

// -------------------------start 1--------------------------

$customer_name_en = $_POST['customer_name_en'];
$keyword = $_GET['keyword'];

$data = $model->getNameENCustomerByAutoComplete($keyword);

echo json_encode($data);

?>