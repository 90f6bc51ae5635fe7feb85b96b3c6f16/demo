<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CheckPayModel.php');
$check_pay_id = $_POST['check_pay_id'];

$cheque_pay_model = new CheckPayModel;

$cheque_pay = $cheque_pay_model->getCheckPayByID($check_pay_id);

echo json_encode($cheque_pay);

?>