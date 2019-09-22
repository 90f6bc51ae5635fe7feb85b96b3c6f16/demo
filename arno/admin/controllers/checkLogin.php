<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
require_once '../../models/UserModel.php';
session_start();
if (isset($_POST['username']) && isset($_POST['password'])) {
    $user_ = $_POST['username'];
    $pass = $_POST['password'];
    $data=[];
    $model = new UserModel;
    $user = $model->getLogin($user_, $pass);
    if (count($user) > 0) {
        $_SESSION['user'] = $user;
        $_SESSION['url'] ="";
        $data ['result'] = true;
    } else {
        $data ['result'] = false;
    }
} else {
   $data ['result'] = false;
}
echo json_encode($data);
?>