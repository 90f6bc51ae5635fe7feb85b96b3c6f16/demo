<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
$data = [];
if (isset($_POST['add'])) {
    $_SESSION['add'] = $_POST['add'];
    $data['result'] = true;
} else {
    $data['result'] = false;
    $data['action'] = "not find session.";
}
echo json_encode($data);
