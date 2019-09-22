<?php  


header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");


require_once('../../models/InvoiceSupplierModel.php');    
$invoice_supplier_model = new InvoiceSupplierModel; 
 

if(isset($_POST['invoice_supplier_code'])){
    
    $invoice_supplier = $invoice_supplier_model->getInvoiceSupplierByCode($_POST['invoice_supplier_code']);
    if(count($invoice_supplier) == 0){
        $data = [];
        $data['invoice_supplier_code'] = $_POST['invoice_supplier_code'];
        $data['invoice_supplier_date'] = $_POST['invoice_supplier_date'];
        $data['invoice_supplier_code_gen'] = $_POST['invoice_supplier_code_gen'];
        $data['invoice_supplier_date_recieve'] = $_POST['invoice_supplier_date_recieve'];
        $data['supplier_id'] = $_POST['supplier_id'];
        $data['invoice_supplier_name'] = $_POST['invoice_supplier_name'];
        $data['invoice_supplier_tax'] = $_POST['invoice_supplier_tax'];
        $data['invoice_supplier_branch'] = $_POST['invoice_supplier_branch'];
        $data['invoice_supplier_address'] = $_POST['invoice_supplier_address'];
        $data['vat_section'] = $_POST['vat_section'];
        $data['vat_section_add'] = $_POST['vat_section_add'];
    
        $data['invoice_supplier_total_price'] = (float)filter_var($_POST['invoice_supplier_total_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_vat_price'] = (float)filter_var($_POST['invoice_supplier_vat_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_net_price'] = $data['invoice_supplier_total_price'] + $data['invoice_supplier_vat_price'];
        
        $data['invoice_supplier_total_price_non'] = (float)filter_var($_POST['invoice_supplier_total_price_non'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_vat_price_non'] = (float)filter_var($_POST['invoice_supplier_vat_price_non'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_total_non'] = (float)filter_var($_POST['invoice_supplier_total_non'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['invoice_supplier_description'] = $_POST['invoice_supplier_description'];
        $data['invoice_supplier_remark'] = $_POST['invoice_supplier_remark'];
        $data['invoice_supplier_begin'] = $_POST['type'];
        $data['addby'] =  $_POST['addby'];
    
        $invoice_supplier_id = $invoice_supplier_model->insertInvoiceSupplier($data);
    
        if($invoice_supplier_id > 0){
            $invoice_supplier = $invoice_supplier_model->getInvoiceSupplierViewByID($invoice_supplier_id);
            
        }
    }
    
    
    echo json_encode($invoice_supplier);
}

?>