<?php  


header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");


require_once('../../models/InvoiceCustomerModel.php');    
$invoice_customer_model = new InvoiceCustomerModel; 
 

if(isset($_POST['invoice_customer_code'])){

    
    $invoice_customer = $invoice_customer_model->getInvoiceCustomerByCode($_POST['invoice_customer_code']);
    if(count($invoice_customer) == 0){
            
        $data = [];
        $data['invoice_customer_code'] = $_POST['invoice_customer_code'];
        $data['invoice_customer_date'] = $_POST['invoice_customer_date'];
        $data['invoice_customer_code_gen'] = $_POST['invoice_customer_code_gen'];
        $data['invoice_customer_date_recieve'] = $_POST['invoice_customer_date_recieve'];
        $data['customer_id'] = $_POST['customer_id'];
        $data['invoice_customer_name'] = $_POST['invoice_customer_name'];
        $data['invoice_customer_tax'] = $_POST['invoice_customer_tax'];
        $data['invoice_customer_branch'] = $_POST['invoice_customer_branch'];
        $data['invoice_customer_address'] = $_POST['invoice_customer_address'];
        $data['vat_section'] = $_POST['vat_section'];
        $data['vat_section_add'] = $_POST['vat_section_add'];

        $data['invoice_customer_total_price'] = (float)filter_var($_POST['invoice_customer_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_customer_vat_price'] = (float)filter_var($_POST['invoice_customer_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_customer_net_price'] = $data['invoice_customer_total_price'] + $data['invoice_customer_vat_price'];
        
        $data['invoice_customer_total_price_non'] = (float)filter_var($_POST['invoice_customer_total_price_non'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_customer_vat_price_non'] = (float)filter_var($_POST['invoice_customer_vat_price_non'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_customer_total_non'] = (float)filter_var($_POST['invoice_customer_total_non'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_customer_description'] = $_POST['invoice_customer_description'];
        $data['invoice_customer_remark'] = $_POST['invoice_customer_remark'];
        $data['invoice_customer_begin'] = $_POST['type'];
        $data['addby'] =  $_POST['addby'];

        $invoice_customer_id = $invoice_customer_model->insertInvoiceCustomer($data);
        
        if($invoice_customer_id > 0){
            $invoice_supplier = $invoice_customer_model->getInvoiceCustomerViewByID($invoice_customer_id);
            
        }
    }
    
    echo json_encode($invoice_supplier);
}

?>