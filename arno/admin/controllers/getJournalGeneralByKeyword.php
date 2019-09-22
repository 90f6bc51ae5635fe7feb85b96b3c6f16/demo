<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/JournalGeneralModel.php');
$keyword = $_GET['keyword'];

$journal_model = new JournalGeneralModel;

$journal = $journal_model->getJournalGeneralByKeyword($keyword );

echo json_encode($journal);

?>