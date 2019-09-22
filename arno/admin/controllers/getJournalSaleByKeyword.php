<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/JournalSaleModel.php');
$keyword = $_GET['keyword'];

$journal_model = new JournalSaleModel;

$journal = $journal_model->getJournalSaleByKeyword($keyword );

echo json_encode($journal);

?>