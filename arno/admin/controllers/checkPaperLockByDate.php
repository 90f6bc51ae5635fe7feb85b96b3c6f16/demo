<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
date_default_timezone_set('asia/bangkok');

require_once('../../models/PaperLockModel.php');
require_once('../../models/UserModel.php');
$paper_lock_model = new PaperLockModel;
$user_model = new UserModel;

$user = $user_model->getUserLicenseByID($_POST['admin_id']);

if(count($user) >  0){ 
    if($user['license_sale_page'] == "Medium" || $user['license_sale_page'] == "High"){
        $lock_1 = "1";
    }else{
        $lock_1 = "0";
    }
    
    if($user['license_account_page'] == "Medium" || $user['license_account_page'] == "High"){
        $lock_2 = "1";
    }else{
        $lock_2 = "0";
    }
    
        
    $result['result'] = $paper_lock_model->checkPaperLockByDate($_POST['date'],$lock_1,$lock_2);

    if($result['result']){
        $result['date_now'] = date("d")."-".date("m")."-".date("Y");
    }else{

    }

}else{
    $result['result'] = true;
    $result['date_now'] = date("d")."-".date("m")."-".date("Y"); 
}

echo json_encode($result);

?>