<?php  


header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");


require_once('../../models/CheckModel.php');
 
$check_model = new CheckModel;
 

if(isset($_POST['check_code'])){
    $data = [];
    $data['check_code'] = $_POST['check_code'];
    $data['check_date_write'] = $_POST['check_date_write'];
    $data['check_date_recieve'] = $_POST['check_date_recieve'];
    $data['bank_id'] = $_POST['bank_id'];
    $data['bank_branch'] = $_POST['bank_branch'];
    $data['customer_id'] = $_POST['customer_id'];
    $data['check_remark'] = $_POST['check_remark'];
    $data['check_total'] = (float)filter_var($_POST['check_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['check_type'] = '0';
    $data['lastupdate'] =  $_POST['lastupdate'];

    $check_id =  $_POST['check_id'];

    $output = $check_model->updateCheckByID($check_id,$data);

    if($output){
        $check = $check_model->getCheckViewByID($check_id);
    }
    
    echo json_encode($check);
}

?>