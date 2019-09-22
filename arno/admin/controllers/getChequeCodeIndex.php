<?php  

require_once('../../models/CheckModel.php');
require_once('../../models/BankModel.php');
require_once('../../models/BankAccountModel.php');
require_once('../../models/SupplierModel.php');
require_once('../../models/UserModel.php');
require_once('../../functions/CodeGenerateFunction.func.php');
require_once('../../models/PaperModel.php');

date_default_timezone_set('asia/bangkok');

$supplier_model = new SupplierModel;
$account_model = new BankAccountModel;
$check_model = new CheckModel;
$user_model = new UserModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('23');

$accounts=$account_model->getBankAccountBy();
$suppliers=$supplier_model->getSupplierBy(); 

$user=$user_model->getUserByID($admin_id);

$data = [];
$data['year'] = date("Y");
$data['month'] = date("m");
$data['number'] = "0000000000";
$data['employee_name'] = $user["user_name_en"];
$data['customer_code'] = $customer["customer_code"];

$code = $code_generate->cut2Array($paper['paper_code'],$data);
$last_code = "";
for($i = 0 ; $i < count($code); $i++){

    if($code[$i]['type'] == "number"){
        $last_code =  $check_model->getCheckLastID($last_code,$code[$i]['length']);
    }else{
        $last_code .= $code[$i]['value'];
    }   
}
echo $last_code;
?>