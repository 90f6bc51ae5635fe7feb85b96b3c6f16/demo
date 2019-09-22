<?php 
require_once('../../models/AddressModel.php');
$address = new AddressModel;
$add_amphur = $address->getZipcodeByAmphurID($_POST['amphur']);
echo $add_amphur['POSTCODE'];
?>