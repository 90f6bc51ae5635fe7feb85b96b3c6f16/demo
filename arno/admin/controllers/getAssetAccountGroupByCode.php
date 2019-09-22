<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/AssetAccountGroupModel.php');
$asset_model = new AssetAccountGroupModel;
$asset = $asset_model->getAssetAccountGroupByCode($_POST['asset_account_group_code']);

echo json_encode($asset);

?>