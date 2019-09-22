<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/JournalCashPaymentModel.php');
$journal_cash_payment_code = $_POST['journal_cash_payment_code'];

$journal_model = new JournalCashPaymentModel;

$journal = $journal_model->getJournalCashPaymentByCode($journal_cash_payment_code );

echo json_encode($journal);

?>