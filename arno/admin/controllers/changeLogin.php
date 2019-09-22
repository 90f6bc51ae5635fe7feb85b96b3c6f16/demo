<?php
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
require_once '../../models/UserModel.php';
session_start();
if (isset($_SESSION['user'])) {
    $user_ = $_SESSION['user']['user_username'];
    $pass = $_SESSION['user']['user_password'];
    $data=[];
    $model = new UserModel;
    $user = $model->getLogin($user_, $pass);
    if (count($user) > 0) {
        $_SESSION['user'] = $user;
        $_SESSION['url'] ="";
        $data ['result'] = true;
        $data ['action'] = "login success.";
    } else {
        $data ['result'] = false;
        $data ['action'] = "login fail.";
    }
} else {
   $data ['result'] = false;
   $data ['action'] = "not find session.";
}
echo json_encode($data);
?>