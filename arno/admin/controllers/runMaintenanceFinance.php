<?php 
require_once('../../models/MaintenanceFinanceModel.php');
$maintenance_model = new MaintenanceFinanceModel;
$maintenance_model->runMaintenance();
echo true;
?>