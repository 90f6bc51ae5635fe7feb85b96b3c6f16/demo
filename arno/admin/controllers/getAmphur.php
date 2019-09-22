<?php 
require_once('../../models/AddressModel.php');
$address = new AddressModel;
$add_amphur = $address->getAmphurByProviceID($_POST['province']);
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($add_amphur); $i++){
?>
<option value="<?php echo $add_amphur[$i]['AMPHUR_NAME']?>"><?php echo $add_amphur[$i]['AMPHUR_NAME']?></option>
<?
}

?>