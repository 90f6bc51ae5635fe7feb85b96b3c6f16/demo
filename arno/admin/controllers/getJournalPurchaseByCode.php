<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/JournalPurchaseModel.php');
$journal_purchase_code = $_POST['journal_purchase_code'];

$journal_model = new JournalPurchaseModel;

$journal = $journal_model->getJournalPurchaseByCode($journal_purchase_code );

echo json_encode($journal);

?>