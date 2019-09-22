<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/JournalSaleModel.php');
$journal_sale_code = $_POST['journal_sale_code'];

$journal_model = new JournalSaleModel;

$journal = $journal_model->getJournalSaleByCode($journal_sale_code );

echo json_encode($journal);

?>