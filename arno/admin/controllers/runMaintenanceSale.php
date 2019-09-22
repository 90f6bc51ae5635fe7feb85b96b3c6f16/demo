<?php 
require_once('../../models/MaintenanceSaleModel.php');
$maintenance_model = new MaintenanceSaleModel;
$maintenance_model->runMaintenance();
echo true;
?>