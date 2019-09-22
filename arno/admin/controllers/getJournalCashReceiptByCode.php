<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/JournalCashReceiptModel.php');
$journal_cash_receipt_code = $_POST['journal_cash_receipt_code'];

$journal_model = new JournalCashReceiptModel;

$journal = $journal_model->getJournalCashReceiptByCode($journal_cash_receipt_code );

echo json_encode($journal);

?>