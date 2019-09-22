<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/JournalSaleReturnModel.php');
$journal_sale_return_code = $_POST['journal_sale_return_code'];

$journal_model = new JournalSaleReturnModel;

$journal = $journal_model->getJournalSaleReturnByCode($journal_sale_return_code );

echo json_encode($journal);

?>