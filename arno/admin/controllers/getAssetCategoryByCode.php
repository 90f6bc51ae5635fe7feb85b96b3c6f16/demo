<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/AssetCategoryModel.php');
$asset_model = new AssetCategoryModel;
$asset = $asset_model->getAssetCategoryByCode($_POST['asset_category_code']);

echo json_encode($asset);

?>