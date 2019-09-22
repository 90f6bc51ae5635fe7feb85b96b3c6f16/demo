<?php
session_start();
$user = $_SESSION['user'];

require_once('../models/PurchaseOrderModel.php');
require_once('../models/PurchaseOrderListModel.php');
require_once('../models/UserModel.php');
require_once('../models/NotificationModel.php');
require_once('../models/ProductModel.php');
require_once('../models/SupplierModel.php');
date_default_timezone_set('asia/bangkok');

$path = "modules/purchase_order/views/";
$user_model = new UserModel;
$supplier_model = new SupplierModel;
$notification_model = new NotificationModel;
$purchase_order_model = new PurchaseOrderModel;
$purchase_order_list_model = new PurchaseOrderListModel;
$product_model = new ProductModel;
$first_char = "PO";
$purchase_order_id = $_GET['id'];
$notification_id = $_GET['notification'];
$supplier_id = $_GET['supplier_id'];
if ($_GET['action'] == 'checking'){
    $products=$product_model->getProductBy();
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $purchase_order = $purchase_order_model->getPurchaseOrderByID($purchase_order_id);
    $supplier=$supplier_model->getSupplierByID($purchase_order['supplier_id']);
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_id);
    require_once($path.'check.inc.php');

}else if ($_GET['action'] == 'sending'){
    $products=$product_model->getProductBy();
    $suppliers=$supplier_model->getSupplierBy();
    $users=$user_model->getUserBy();
    $purchase_order = $purchase_order_model->getPurchaseOrderByID($purchase_order_id);
    $supplier=$supplier_model->getSupplierByID($purchase_order['supplier_id']);
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_id);
    require_once($path.'send.inc.php');

}else if ($_GET['action'] == 'cancelled'){ 
    $user_license = $_SESSION['user'];
    $purchase_order = $purchase_order_model->getPurchaseOrderViewByID($purchase_order_id);
    $purchase_order_lists = $purchase_order_list_model->getPurchaseOrderListBy($purchase_order_id);
    
    if($supplier['vat_type'] == '0'){
        $vat= '0';
    }else{
        $vat = $purchase_order['vat'];
    } 
    $supplier=$supplier_model->getSupplierByID($purchase_order['supplier_id']);
    if($date_start == ""){
        $date_start = date('01-m-Y'); 
    }
    
    if($date_end == ""){ 
        $date_end  = date('t-m-Y');
    } 
    require_once($path.'cancelled.inc.php');

}else if ($_GET['action'] == 'update_checking'){
    
    if(isset($_POST['purchase_order_list_id'])){

        $purchase_order_list_id = $_POST['purchase_order_list_id'];
        $purchase_order_list_supplier_qty = $_POST['purchase_order_list_supplier_qty'];
        $purchase_order_list_supplier_delivery_min = $_POST['purchase_order_list_supplier_delivery_min'];
        $purchase_order_list_supplier_delivery_max = $_POST['purchase_order_list_supplier_delivery_max'];
        $purchase_order_list_supplier_remark = $_POST['purchase_order_list_supplier_remark'];

        if(is_array($purchase_order_list_id)){
            for($i=0; $i < count($purchase_order_list_id) ; $i++){
                $data_sub = [];
                $data_sub['purchase_order_list_supplier_qty'] = $purchase_order_list_supplier_qty[$i];
                $data_sub['purchase_order_list_supplier_delivery_min'] = $purchase_order_list_supplier_delivery_min[$i];
                $data_sub['purchase_order_list_supplier_delivery_max'] = $purchase_order_list_supplier_delivery_max[$i];
                $data_sub['purchase_order_list_supplier_remark'] = $purchase_order_list_supplier_remark[$i];
    
                $purchase_order_list_model->updatePurchaseOrderListById($data_sub,$purchase_order_list_id[$i]);
            }
        }else if($purchase_order_list_id != ""){
            $data_sub = [];
            $data_sub['purchase_order_list_supplier_qty'] = $purchase_order_list_supplier_qty;
            $data_sub['purchase_order_list_supplier_delivery_min'] = $purchase_order_list_supplier_delivery_min;
            $data_sub['purchase_order_list_supplier_delivery_max'] = $purchase_order_list_supplier_delivery_max;
            $data_sub['purchase_order_list_supplier_remark'] = $purchase_order_list_supplier_remark;

            $purchase_order_list_model->updatePurchaseOrderListById($data_sub,$purchase_order_list_id);
        }
        
        $data = [];
        $data['purchase_order_status'] = 'New';
        $output = $purchase_order_model->updatePurchaseOrderStatusByID($purchase_order_id,$data);


        if($output){
            $notification_model->setNotification("Purchase Order",$purchase_order_id,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']." has been update from supplier","index.php?app=purchase_order&action=detail&id=$purchase_order_id","license_purchase_page",'Medium');
            $notification_model->setNotification("Purchase Order",$purchase_order_id,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']."has been update from supplier","index.php?app=purchase_order&action=detail&id=$purchase_order_id","license_purchase_page",'High');  
           
    ?>
            <script>
                alert("Update checking complete.");
                window.location="index.php?app=purchase_order&action=checking&id=<?php echo $purchase_order_id;?>"
            </script>
    <?php
        }else{
            ?>
            <script>window.history.back();</script>
            <?php
        }
    
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
        
    
}else if ($_GET['action'] == 'update_sending'){
    
    if(isset($_POST['purchase_order_list_id'])){

        $purchase_order_list_id = $_POST['purchase_order_list_id'];
        $purchase_order_list_supplier_qty = $_POST['purchase_order_list_supplier_qty'];
        $purchase_order_list_supplier_delivery_min = $_POST['purchase_order_list_supplier_delivery_min'];
        $purchase_order_list_supplier_delivery_max = $_POST['purchase_order_list_supplier_delivery_max'];
        $purchase_order_list_supplier_remark = $_POST['purchase_order_list_supplier_remark'];

        if(is_array($purchase_order_list_id)){
            for($i=0; $i < count($purchase_order_list_id) ; $i++){
                $data_sub = [];
                $data_sub['purchase_order_list_supplier_qty'] = $purchase_order_list_supplier_qty[$i];
                $data_sub['purchase_order_list_supplier_delivery_min'] = $purchase_order_list_supplier_delivery_min[$i];
                $data_sub['purchase_order_list_supplier_delivery_max'] = $purchase_order_list_supplier_delivery_max[$i];
                $data_sub['purchase_order_list_supplier_remark'] = $purchase_order_list_supplier_remark[$i];
    
                $purchase_order_list_model->updatePurchaseOrderListById($data_sub,$purchase_order_list_id[$i]);
            }
        }else if($purchase_order_list_id != ""){
            $data_sub = [];
            $data_sub['purchase_order_list_supplier_qty'] = $purchase_order_list_supplier_qty;
            $data_sub['purchase_order_list_supplier_delivery_min'] = $purchase_order_list_supplier_delivery_min;
            $data_sub['purchase_order_list_supplier_delivery_max'] = $purchase_order_list_supplier_delivery_max;
            $data_sub['purchase_order_list_supplier_remark'] = $purchase_order_list_supplier_remark;

            $purchase_order_list_model->updatePurchaseOrderListById($data_sub,$purchase_order_list_id);
        }
        
        $data = [];
        $data['purchase_order_status'] = 'Confirm';
        $output = $purchase_order_model->updatePurchaseOrderStatusByID($purchase_order_id,$data);


        if($output){

            //$notification_model->setNotificationByUserID("Purchase Order",$purchase_order_id,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']." has been confirm from supplier","index.php?app=purchase_order&action=detail&id=$purchase_order_id",$purchase_order['employee_id']);
            $notification_model->setNotification("Purchase Order",$purchase_order_id,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']." has been confirm from supplier","index.php?app=purchase_order&action=detail&id=$purchase_order_id","license_purchase_page",'Medium');
            $notification_model->setNotification("Purchase Order",$purchase_order_id,"Purchase Order <br>No. ".$purchase_order['purchase_order_code']."has been confirm from supplier","index.php?app=purchase_order&action=detail&id=$purchase_order_id","license_purchase_page",'High');  
        
            
    ?>
            <script>
                alert("Update confirm complete.");
                window.location="index.php?app=purchase_order&action=sending&id=<?php echo $purchase_order_id;?>"
            </script>
    <?php
        }else{
            ?>
            <script>window.history.back();</script>
            <?php
        }
    
    }else{
        ?>
    <script>window.history.back();</script>
        <?php
    }
        
        
    
}




?>