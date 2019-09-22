<?php  


header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");


require_once('../../models/CheckPayModel.php');
 
$check_model = new CheckPayModel;
 

if(isset($_POST['check_pay_code'])){
    $data = [];
    $data['check_pay_code'] = $_POST['check_pay_code'];
    $data['check_pay_date_write'] = $_POST['check_pay_date_write'];
    $data['check_pay_date'] = $_POST['check_pay_date'];
    $data['bank_account_id'] = $_POST['bank_account_id'];
    $data['supplier_id'] = $_POST['supplier_id'];
    $data['check_pay_remark'] = $_POST['check_pay_remark'];
    $data['check_pay_total'] = (float)filter_var($_POST['check_pay_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $data['check_type'] = '0';
    $data['lastupdate'] =  $_POST['lastupdate'];

    $check_pay_id =  $_POST['check_pay_id'];

    $output = $check_model->updateCheckPayByID($check_pay_id,$data);

    if($output){
        $check_pay = $check_model->getCheckPayViewByID($check_pay_id);
        
    }
    
    echo json_encode($check_pay);
}

?>