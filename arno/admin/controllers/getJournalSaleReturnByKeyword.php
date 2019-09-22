<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/JournalSaleReturnModel.php');
$keyword = $_GET['keyword'];

$journal_model = new JournalSaleReturnModel;

$journal = $journal_model->getJournalSaleReturnByKeyword($keyword );

echo json_encode($journal);

?>