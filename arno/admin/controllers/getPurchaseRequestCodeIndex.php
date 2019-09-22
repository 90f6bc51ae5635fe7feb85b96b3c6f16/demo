<?php 
require_once('../../models/PurchaseRequestModel.php');
require_once('../../models/CustomerModel.php');
require_once('../../functions/CodeGenerateFunction.func.php');
require_once('../../models/PaperModel.php');
require_once('../../models/UserModel.php');

date_default_timezone_set('asia/bangkok');
$user_model = new UserModel;
$customer_model = new CustomerModel;
$purchase_request_model = new PurchaseRequestModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('9');

$user=$user_model->getUserByID($_POST['employee_id']); 

$data = [];
$data['year'] = date("Y");
$data['month'] = date("m");
$data['number'] = "0000000000";
$data['employee_name'] = $user["user_name"];

$code = $code_generate->cut2Array($paper['paper_code'],$data);
$last_code = "";
for($i = 0 ; $i < count($code); $i++){

    if($code[$i]['type'] == "number"){
        $last_code = $purchase_request_model->getPurchaseRequestLastID($last_code,$code[$i]['length']);
    }else{
        $last_code .= $code[$i]['value'];
    }   
}
echo $last_code;
?>