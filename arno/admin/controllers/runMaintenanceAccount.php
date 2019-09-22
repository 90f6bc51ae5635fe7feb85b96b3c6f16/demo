<?php 
require_once('../../models/MaintenanceAccountModel.php');
$maintenance_model = new MaintenanceAccountModel;
$maintenance_model->runMaintenance();
echo true;
?>