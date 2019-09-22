<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CustomerPurchaseOrderModel.php');
$customer_purchase_order_code = $_POST['customer_purchase_order_code']; 
$keyword = $_GET['keyword'];
$model = new CustomerPurchaseOrderModel;

$data = $model->getCustomerPurchaseOrderByAutoComplete($keyword);

echo json_encode($data);

?>