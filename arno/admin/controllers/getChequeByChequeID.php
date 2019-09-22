<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CheckModel.php');
$check_id = $_POST['check_id'];

$cheque_model = new CheckModel;

$cheque = $cheque_model->getCheckByID($check_id);

echo json_encode($cheque);

?>