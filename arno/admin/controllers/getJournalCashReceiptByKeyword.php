<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/JournalCashReceiptModel.php');
$keyword = $_GET['keyword'];

$journal_model = new JournalCashReceiptModel;

$journal = $journal_model->getJournalCashReceiptByKeyword($keyword );

echo json_encode($journal);

?>