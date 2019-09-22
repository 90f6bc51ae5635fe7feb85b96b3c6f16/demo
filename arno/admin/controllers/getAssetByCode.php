<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/AssetModel.php');
$asset_model = new AssetModel;
$asset = $asset_model->getAssetByCode($_POST['asset_code']);

echo json_encode($asset);

?>