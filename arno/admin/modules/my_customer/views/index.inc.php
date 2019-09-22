<?php
require_once('../models/UserModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/CustomerTypeModel.php');
require_once('../models/CustomerSizeModel.php');
require_once('../models/CustomerGroupModel.php');
require_once('../models/InvoiceCustomerModel.php');
require_once('../models/AccountModel.php');
require_once('../models/CurrencyModel.php');

$path = "modules/my_customer/views/";
$target_dir = "../upload/customer/";

$invoice_customer_model = new InvoiceCustomerModel;
$currency_model = new CurrencyModel;
$customer_type_model = new CustomerTypeModel;
$customer_size_model = new CustomerSizeModel;
$customer_group_model = new CustomerGroupModel;
$model_user = new UserModel;
$customer_model = new CustomerModel;
$account_model = new AccountModel;

$customer_id = $_GET['id'];

//---------------------ฟังก์ชั่นวันที่------------------------------------
date_default_timezone_set("Asia/Bangkok");
$d1=date("d");
$d2=date("m");
$d3=date("Y");
$d4=date("H");
$d5=date("i");
$d6=date("s");
$date="$d1$d2$d3$d4$d5$d6";
//---------------------------------------------------------------------


//-----------------ฟังก์ชั่นสุ่มตัวเลข----------------
$numrand = (mt_rand());
//-----------------------------------------------


if(!isset($_GET['action'])){


    $keyword = $_GET['keyword'];
    $keyword_end = $_GET['keyword_end'];
    $customer_type = $_GET['customer_type'];
    $customer_end_user_type = $_GET['customer_end_user_type'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 100;
    
    $customer = $customer_model->getCustomerBySale($admin_id,$customer_type,$customer_end_user_type,$keyword,$keyword_end);
    $customer_types = $customer_type_model->getCustomerTypeBy();
    $page_max = (int)(count($customer)/$page_size);
    if(count($customer)%$page_size > 0){
        $page_max += 1;
    }
    
    require_once($path.'view.inc.php');
    

}else if ($_GET['action'] == 'insert'){

    $user = $model_user->getUserBy('','sale');
    $account = $account_model->getAccountNode();
    $currency = $currency_model->getCurrencyBy();
    $customer_types = $customer_type_model->getCustomerTypeBy();
    $customer_sizes = $customer_size_model->getCustomerSizeBy();
    $customer_groups = $customer_group_model->getCustomerGroupBy();
    $customers = $customer_model->getCustomerNotEndUserBy();
    if($license_manager_page == 'High' || $license_sale_employee_page == 'High'){
        $customer_approve = 'Approved'; 
    }else{ 
        $customer_approve = 'Request';
    }
    require_once($path.'insert.inc.php');

}else if ($_GET['action'] == 'update'){

    $customer_id = $_GET['id'];
    $customer = $customer_model->getCustomerByID($customer_id);
    $user = $model_user->getUserBy('','sale');
    $account = $account_model->getAccountNode();
    $currency = $currency_model->getCurrencyBy();
    $customer_types = $customer_type_model->getCustomerTypeBy();
    $customer_sizes = $customer_size_model->getCustomerSizeBy();
    $customer_groups = $customer_group_model->getCustomerGroupBy();
    $customers = $customer_model->getCustomerNotEndUserBy();

   require_once($path.'update.inc.php');

}else if ($_GET['action'] == 'detail'){   
    
    $customer = $customer_model->getCustomerViewByID($_GET['id']);

    $header_page = "รายละเอียดลูกค้า";
    $product = $customer_model->getCustomerProductBy($_GET['id']);
    $invoice = $customer_model->getCustomerProductInvoiceBy($_GET['id']);
    // $quotation = $customer_model-> getCustomerProductQuotationBy($_GET['id']); 
    $quotation = $customer_model-> getCustomerQuotationBy($_GET['id']); 
   require_once($path.'detail.inc.php');

}else if ($_GET['action'] == 'delete' && ($license_sale_employee_page == 'High') ){

    $customer = $customer_model->getCustomerByID($_GET['id']);
    if(count($customer) > 0){
       
        $target_file = $target_dir . $customer["customer_logo"];
        if (file_exists($target_file)) {
            unlink($target_file);
        }
        $user = $customer_model->deleteCustomerByID($_GET['id']);
    }
    
    ?>
        <script>window.location="index.php?app=my_customer"</script>
    <?php

}else if ($_GET['action'] == 'add'){
    $customer_exist = $customer_model->getCustomerByCodeArray( $_POST['customer_code']);
     
    if(count($customer_exist) > 0){  ?>
         <script>
            alert('ชื่อลูกค้า ซ้ำ <?php echo $_POST['customer_code']; ?>'); 
            window.location="index.php?app=my_customer"; 
        </script>
    <?php 
        return ;
    }
    if(isset($_POST['customer_code'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_code'];
        $data['customer_code'] = $_POST['customer_code'];
        $data['customer_name_th'] = $_POST['customer_name_th'];
        $data['customer_name_en'] = $_POST['customer_name_en'];
        $data['customer_type'] = $_POST['customer_type'];
        $data['customer_tax'] = $_POST['customer_tax'];
        $data['customer_domestic'] = $_POST['customer_domestic'];
        $data['customer_tel'] = $_POST['customer_tel'];
        $data['customer_fax'] = $_POST['customer_fax'];
        $data['customer_email'] = $_POST['customer_email'];
        $data['customer_address_1'] = $_POST['customer_address_1'];
        $data['customer_address_2'] = $_POST['customer_address_2'];
        $data['customer_address_3'] = $_POST['customer_address_3'];
        $data['customer_zipcode'] = $_POST['customer_zipcode'];
        $data['customer_branch'] = $_POST['customer_branch'];
        $data['customer_remark'] = $_POST['customer_remark'];
        $data['customer_zone'] = $_POST['customer_zone'];
        $data['credit_day'] = 30;//$_POST['credit_day'];
        $data['condition_pay'] = 0;//$_POST['condition_pay'];
        // $data['pay_limit'] = $_POST['pay_limit'];
        $data['account_id'] = 15;//$_POST['account_id'];
        $data['sale_id'] = $_POST['sale_id'];
        $data['customer_type_id'] = 0;//$_POST['customer_type_id'];
        $data['customer_size_id'] = 0;//$_POST['customer_size_id'];
        $data['customer_group_id'] = 1;//$_POST['customer_group_id'];
        $data['vat_type'] = 2;//$_POST['vat_type'];
        $data['vat'] = 7;//$_POST['vat'];
        $data['currency_id'] = 117;//$_POST['currency_id'];
        $data['customer_end_user_type'] =  $_POST['customer_end_user_type'];
        $data['customer_end_user'] =  0;//$_POST['customer_end_user'];
        $data['customer_approve'] =  $_POST['customer_approve'];
        $data['addby'] = $admin_id;
        $check = true;

        if($_FILES['customer_logo']['name'] == ""){
            $data['customer_logo'] = 'default.png';
        }else{
            
            
            //---------เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล----------
            $type = strrchr($_FILES['customer_logo']['name'],".");
            //--------------------------------------------------
            
            //-----ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม---------
            $newname = $date.$numrand.$type;
            $path_copy=$path.$newname;
            $path_link=$target_dir.$newname;
            //-------------------------------------------------

            $target_file = $target_dir .$date.$newname;

            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["customer_logo"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["customer_logo"]["tmp_name"], $target_file)) {
                
                
                //-----------------------------------
                $data['customer_logo'] = $date.$newname;
                //-----------------------------------


            } else {
                $error_msg =  "Sorry, there was an error uploading your file.";
                $check = false;
            } 
        }

        if($check == false){
    ?>
        <script>
            alert('<?php echo $error_msg; ?>');
            window.history.back();
        </script>
    <?php
        }else{
            $id = $customer_model->insertCustomer($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=my_customer"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=my_customer&action=add"</script>
    <?php
            }
                    
        }
    }else{
        ?>
    <script>window.location="index.php?app=my_customer"</script>
        <?php
    }
    
}else if ($_GET['action'] == 'edit'){
    if(isset($_POST['customer_code'])){
        $data = [];
        $data['customer_id'] = $_POST['customer_code'];
        $data['customer_code'] = $_POST['customer_code'];
        $data['customer_name_th'] = $_POST['customer_name_th'];
        $data['customer_name_en'] = $_POST['customer_name_en'];
        $data['customer_type'] = $_POST['customer_type'];
        $data['customer_tax'] = $_POST['customer_tax'];
        $data['customer_address_1'] = $_POST['customer_address_1'];
        $data['customer_address_2'] = $_POST['customer_address_2'];
        $data['customer_address_3'] = $_POST['customer_address_3'];
        $data['customer_zipcode'] = $_POST['customer_zipcode'];
        $data['customer_tel'] = $_POST['customer_tel'];
        $data['customer_fax'] = $_POST['customer_fax'];
        $data['customer_email'] = $_POST['customer_email'];
        $data['customer_domestic'] = $_POST['customer_domestic'];
        $data['customer_branch'] = $_POST['customer_branch'];
        $data['customer_remark'] = $_POST['customer_remark'];
        $data['customer_zone'] = $_POST['customer_zone'];
        $data['sale_id'] = $_POST['sale_id'];
        $data['customer_approve'] =  $_POST['customer_approve'];
        $data['updateby'] = $admin_id;
        // $data['credit_day'] = $_POST['credit_day'];
        // $data['condition_pay'] = $_POST['condition_pay'];
        // $data['pay_limit'] = $_POST['pay_limit'];
        // $data['account_id'] = $_POST['account_id']; 
        // $data['customer_type_id'] = $_POST['customer_type_id'];
        // $data['customer_size_id'] = $_POST['customer_size_id'];
        // $data['customer_group_id'] = $_POST['customer_group_id'];
        // $data['vat_type'] = $_POST['vat_type'];
        // $data['vat'] = $_POST['vat'];
        // $data['currency_id'] = $_POST['currency_id'];
        // $data['customer_end_user_type'] =  $_POST['customer_end_user_type'];
        // $data['customer_end_user'] =  $_POST['customer_end_user'];
        

        $check = true;

        if($_FILES['customer_logo']['name'] == ""){
            $data['customer_logo'] = $_POST['customer_logo_o'];
        }else {
            

            
            //---------เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล----------
            $type = strrchr($_FILES['customer_logo']['name'],".");
            //--------------------------------------------------
            
            //-----ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม---------
            $newname = $date.$numrand.$type;
            $path_copy=$path.$newname;
            $path_link=$target_dir.$newname;
            //-------------------------------------------------

            $target_file = $target_dir .$date.$newname;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            if (file_exists($target_file)) {
                $error_msg =  "Sorry, file already exists.";
                $check = false;
            }else if ($_FILES["customer_logo"]["size"] > 500000) {
                $error_msg = "Sorry, your file is too large.";
                $check = false;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
                $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $check = false;
            }else if (move_uploaded_file($_FILES["customer_logo"]["tmp_name"], $target_file)) {

                //-----------------------------------
                $data['customer_logo'] = $date.$newname;
                //-----------------------------------

                $target_file = $target_dir . $_POST["customer_logo_o"];
                if($_POST["customer_logo_o"] != 'default.png'){
                    if (file_exists($target_file)) {
                        unlink($target_file);
                    }
                }
            } else {
                $error_msg =  "Sorry, there was an error uploading your file.";
                $check = false;
            } 
        }

        if($check == false){
    ?>
        <script>
            alert('<?php echo $error_msg; ?>');
            window.history.back();
        </script>
    <?php
        }else{
            $id = $customer_model->updateCustomerByID($_POST['customer_id'],$data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=my_customer"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=my_customer&action=update&id=<?php echo $_POST['customer_id'];?>"</script>
    <?php
            }
                    
        }
    }else{
        ?>
    <script>window.location="index.php?app=my_customer"</script>
        <?php
    }
    
}else{

    $keyword = $_GET['keyword'];
    $keyword_end = $_GET['keyword_end'];
    $customer_type = $_GET['customer_type'];
    $customer_end_user_type = $_GET['customer_end_user_type'];

    if($_GET['page'] == '' || $_GET['page'] == '0'){
        $page = 0;
    }else{
        $page = $_GET['page'] - 1;
    }

    $page_size = 100;
    
    $customer = $customer_model->getCustomerBySale($admin_id,$customer_type,$customer_end_user_type,$keyword,$keyword_end);
    $customer_types = $customer_type_model->getCustomerTypeBy();
    $page_max = (int)(count($customer)/$page_size);
    if(count($customer)%$page_size > 0){
        $page_max += 1;
    }
    
    require_once($path.'view.inc.php');

}





?>