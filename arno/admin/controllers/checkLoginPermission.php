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
        $data ['action'] = "login success.";
        $data ['result'] = true;
    } else {
        $data ['action'] = "login fail.";
        $data ['result'] = false;
    }
} else {
    $data ['action'] = "not find session.";
    $data ['result'] = false;
}
echo json_encode($data);
?>