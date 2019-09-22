<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
require_once('../../models/RegrindSupplierModel.php');
session_start();

date_default_timezone_set('asia/bangkok');
// Getting the received JSON into $json variable.
$json = file_get_contents('php://input');

 // decoding the received JSON and store into $obj variable.
$obj = json_decode($json,true);

// Populate User email from JSON $obj array and store into $email.
$get_data = $obj['data'];



    $data=[];
    $regrind_supplier_model = new RegrindSupplierModel; 
    $customer_id = $_POST['customer_id'];
    // $date_start = $obj['date_start'];
    // $date_end = $obj['date_end'];  
    $date_start = "";
    $date_end = "";  
    $keyword = $obj['keyword'];   
    // if($date_start == ""){
    //     $date_start = date('01-m-Y'); 
    // }    
    // if($date_end == ""){
    //     $date_end  = date('t-m-Y'); 
    // }
    $regrind_suppliers = $regrind_supplier_model->getRegrindSupplierByCustomerID($date_start,$date_end,$customer_id,$keyword); 
    if (count($regrind_suppliers) > 0) {
        $data ['regrind_suppliers'] = $regrind_suppliers ;       
        $data ['customer_id'] = $customer_id ;       
        $data ['result'] = true;
    } else {
        $data ['result'] = false;
    } 

    $data ['date_start'] = $date_start;
    $data ['date_end'] = $date_end;
    $data ['keyword'] = $keyword;
echo json_encode($data); 
// echo $regrind_suppliers;
// echo $data;

?>