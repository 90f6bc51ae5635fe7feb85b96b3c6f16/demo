<?php  


header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");


require_once('../../models/CheckModel.php');    
$check_model = new CheckModel; 
 

if(isset($_POST['check_code'])){
    $check = $check_model->getCheckByCode($_POST['check_code']);

    if(count($check) == 0){
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
        $data['addby'] =  $_POST['addby'];
    
        $check_id = $check_model->insertCheck($data);
    
        if($check_id > 0){
            $check = $check_model->getCheckViewByID($check_id);
            
        }
    }
    
    
    echo json_encode($check);
}

?>