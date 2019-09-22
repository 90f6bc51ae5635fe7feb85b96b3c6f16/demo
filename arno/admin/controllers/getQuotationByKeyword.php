<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/QuotationModel.php');
$quotation_contact_name = $_POST['quotation_contact_name'];
$keyword = $_POST['keyword'];

$quotation_model = new QuotationModel;

$quotation = $quotation_model->getQuotationContactBy($quotation_contact_name,$keyword);

echo json_encode($quotation);
// echo $quotation;
?>