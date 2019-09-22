<?php  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");


require_once('../../models/CheckModel.php');
 
$check_model = new CheckModel;
 
if(isset($_POST['check_id'])){
    $check_id =  $_POST['check_id'];
    $checks = $check_model->deleteCheckById($check_id);
    echo json_encode($checks);
}

?>