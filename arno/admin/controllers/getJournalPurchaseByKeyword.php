<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/JournalPurchaseModel.php');
$keyword = $_GET['keyword'];

$journal_model = new JournalPurchaseModel;

$journal = $journal_model->getJournalPurchaseByKeyword($keyword );

echo json_encode($journal);

?>