<?php 
require_once('../../models/MaintenanceStockModel.php');
$maintenance_model = new MaintenanceStockModel;
$maintenance_model->runMaintenance();
echo true;
?>