<?php 

require_once('../../models/SupplierModel.php');
require_once('../../models/InvoiceSupplierModel.php');
require_once('../../functions/CodeGenerateFunction.func.php');
require_once('../../models/PaperModel.php');
require_once('../../models/UserModel.php');

date_default_timezone_set('asia/bangkok');

$user_model = new UserModel;
$supplier_model = new SupplierModel;
$invoice_supplier_model = new InvoiceSupplierModel;

$code_generate = new CodeGenerate;
$paper_model = new PaperModel;

// 9 = key ของ purchase request ใน tb_paper
$paper = $paper_model->getPaperByID('13');


$supplier=$supplier_model->getSupplierByID($_POST['supplier_id']);

$user=$user_model->getUserByID($_POST['employee_id']);

$data = [];
$data['year'] = date("Y");
$data['month'] = date("m");
$data['number'] = "0000000000";
$data['employee_name'] = $user["user_name_en"];
$data['supplier_code'] = $supplier["supplier_code"];

if($supplier['supplier_domestic'] == "ภายในประเทศ"){
    $paper = $paper_model->getPaperByID('12');
}else{
    $paper = $paper_model->getPaperByID('13');
}

$code = $code_generate->cut2Array($paper['paper_code'],$data);
$last_code = "";
for($i = 0 ; $i < count($code); $i++){

    if($code[$i]['type'] == "number"){
        $last_code = $invoice_supplier_model->getInvoiceSupplierLastID($last_code,$code[$i]['length']);
    }else{
        $last_code .= $code[$i]['value'];
    }   
} 

echo $last_code;
?>