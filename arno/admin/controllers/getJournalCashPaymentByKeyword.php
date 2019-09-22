<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/JournalCashPaymentModel.php');
$keyword = $_GET['keyword'];

$journal_model = new JournalCashPaymentModel;

$journal = $journal_model->getJournalCashPaymentByKeyword($keyword );

echo json_encode($journal);

?>