<?php 


require_once('../../models/QuotationSupplierModel.php');
require_once('../../models/SupplierModel.php');
require_once('../../functions/CodeGenerateFunction.func.php');
require_once('../../models/PaperModel.php');
require_once('../../models/UserModel.php');

date_default_timezone_set('asia/bangkok');

$user_model = new UserModel;
$supplier_model = new SupplierModel;
$quotation_supplier_model = new QuotationSupplierModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('36');

$user=$user_model->getUserByID($_POST['employee_id']);
$supplier=$supplier_model->getSupplierByID($_POST['supplier_id']); 

$data = [];
$data['year'] = date("Y");
$data['month'] = date("m");
$data['number'] = "0000000000";
$data['employee_name'] = $user["user_name"];
$data['supplier_code'] = $supplier['supplier_code'];
$data['supplier_name'] = $supplier['supplier_name_en'];

$code = $code_generate->cut2Array($paper['paper_code'],$data);
$last_code = "";
for($i = 0 ; $i < count($code); $i++){

    if($code[$i]['type'] == "number"){
        $last_code = $quotation_supplier_model->getQuotationSupplierLastID($last_code,$code[$i]['length']);
    }else{
        $last_code .= $code[$i]['value'];
    }   
} 
echo $last_code;
?>