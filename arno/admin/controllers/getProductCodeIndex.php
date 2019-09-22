<?php 


require_once('../../models/ProductModel.php');
require_once('../../models/ProductTypeModel.php');
require_once('../../functions/CodeGenerateFunction.func.php'); 

date_default_timezone_set('asia/bangkok');

$product_model = new ProductModel; 
$product_type_model = new ProductTypeModel; 

$code_generate = new CodeGenerate; 
$paper = $product_type_model->getProductTypeByID($_POST['product_type_id']);

$data = [];
$data['year'] = date("Y");
$data['month'] = date("m");
$data['number'] = "0000000000"; 

$code = $code_generate->cut2Array($paper['product_type_first_char'],$data);
$last_code = "";
for($i = 0 ; $i < count($code); $i++){

    if($code[$i]['type'] == "number"){
        $last_code = $product_model->getProductLastID($last_code,$code[$i]['length']);
    }else{
        $last_code .= $code[$i]['value'];
    }   
} 

echo $last_code;
?>