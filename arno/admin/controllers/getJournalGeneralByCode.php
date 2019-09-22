<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/JournalGeneralModel.php');
$journal_general_code = $_POST['journal_general_code'];

$journal_model = new JournalGeneralModel;

$journal = $journal_model->getJournalGeneralByCode($journal_general_code );

echo json_encode($journal);

?>