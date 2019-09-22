<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CheckModel.php');
$keyword = $_GET['keyword'];

$cheque_model = new CheckModel;

$cheques = $cheque_model->getCheckBy('0',$date_start,$date_end,$supplier_id,$keyword);

echo json_encode($cheques);

?>