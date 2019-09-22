<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CustomerModel.php');
$keyword = $_GET['keyword'];

$customer_model = new CustomerModel;

$customers = $customer_model->getCustomerByKeyword($keyword);

echo json_encode($customers);

?>