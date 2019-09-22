<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CustomerModel.php');
$customer_name_th = $_POST['customer_name_th']; 
$keyword = $_GET['keyword'];
$model = new CustomerModel;

$data = $model->getNameTHCustomerByAutoComplete($keyword);

echo json_encode($data);

?>