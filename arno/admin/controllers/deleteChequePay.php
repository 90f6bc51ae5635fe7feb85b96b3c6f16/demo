<?php  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");


require_once('../../models/CheckPayModel.php');
 
$check_model = new CheckPayModel;
 
if(isset($_POST['check_pay_id'])){
    $check_pay_id =  $_POST['check_pay_id'];
    $checks = $check_model->deleteCheckPayById($check_pay_id);
    echo json_encode($checks);
}

?>