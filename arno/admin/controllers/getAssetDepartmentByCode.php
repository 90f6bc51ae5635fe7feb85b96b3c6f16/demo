<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/AssetDepartmentModel.php');
$asset_model = new AssetDepartmentModel;
$asset = $asset_model->getAssetDepartmentByCode($_POST['asset_department_code']);

echo json_encode($asset);

?>