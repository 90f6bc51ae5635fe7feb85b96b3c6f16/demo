<?php 
require_once('../../models/AddressModel.php');
$address = new AddressModel;
$add_district = $address->getDistricByAmphurID($_POST['amphur']);
?>
<option value="">Select</option>
<?php
for($i=0; $i < count($add_district); $i++){
?>
<option value="<?php echo $add_district[$i]['DISTRICT_NAME']?>"><?php echo $add_district[$i]['DISTRICT_NAME']?></option>
<?
}

?>