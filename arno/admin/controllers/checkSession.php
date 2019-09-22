<?php
session_start();
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
$data= [];
if(!isset($_SESSION['user'])){
    $data ['result'] = false;
}
else{
    $data ['result'] = true;
}
echo json_encode($data);
?>